<?php

namespace App\Services\Attendance;

use App\Models\Company;
use App\Models\CompanyHoliday;
use App\Models\CompanyWorkSchedule;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class WorkCalendarService
{
    private const DAY_FIELDS = [
        1 => 'monday', 2 => 'tuesday', 3 => 'wednesday', 4 => 'thursday',
        5 => 'friday', 6 => 'saturday', 7 => 'sunday',
    ];

    public function scheduleFor(Company $company): CompanyWorkSchedule
    {
        return CompanyWorkSchedule::firstOrCreate(
            ['company_id' => $company->id],
            [
                'monday' => true, 'tuesday' => true, 'wednesday' => true,
                'thursday' => true, 'friday' => true,
                'saturday' => false, 'sunday' => false,
            ]
        );
    }

    public function holidayFor(Company $company, CarbonInterface $date): ?CompanyHoliday
    {
        return CompanyHoliday::query()
            ->where('company_id', $company->id)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->orderBy('start_date')
            ->first();
    }

    public function isWorkingDay(Company $company, CarbonInterface $date): bool
    {
        if ($this->holidayFor($company, $date)) {
            return false;
        }

        $schedule = $this->scheduleFor($company);
        $field = self::DAY_FIELDS[$date->isoWeekday()] ?? null;

        return $field ? (bool) $schedule->{$field} : false;
    }

    /**
     * Ambil seluruh tanggal kerja dengan satu query jadwal dan satu query hari
     * libur. Method ini dipakai laporan rentang panjang agar tidak menjalankan
     * dua query baru untuk setiap tanggal di dalam periode.
     */
    public function workingDatesBetween(
        Company $company,
        CarbonInterface $start,
        CarbonInterface $end,
    ): Collection {
        $timezone = $company->timezone ?: config('app.timezone');
        $cursor = CarbonImmutable::parse($start->toDateString(), $timezone);
        $last = CarbonImmutable::parse($end->toDateString(), $timezone);

        if ($cursor->greaterThan($last)) {
            [$cursor, $last] = [$last, $cursor];
        }

        $schedule = $this->scheduleFor($company);
        $holidays = CompanyHoliday::query()
            ->where('company_id', $company->id)
            ->whereDate('start_date', '<=', $last->toDateString())
            ->whereDate('end_date', '>=', $cursor->toDateString())
            ->get(['start_date', 'end_date']);
        $dates = collect();

        while ($cursor->lessThanOrEqualTo($last)) {
            $field = self::DAY_FIELDS[$cursor->isoWeekday()] ?? null;
            $holiday = $holidays->contains(
                fn (CompanyHoliday $row): bool => $row->start_date->lte($cursor)
                    && $row->end_date->gte($cursor)
            );

            if ($field && (bool) $schedule->{$field} && ! $holiday) {
                $dates->push($cursor);
            }

            $cursor = $cursor->addDay();
        }

        return $dates;
    }

    public function workingDaysBetween(
        Company $company,
        CarbonInterface $start,
        CarbonInterface $end,
    ): int {
        return $this->workingDatesBetween($company, $start, $end)->count();
    }

    public function dayInfo(Company $company, CarbonInterface $date): array
    {
        $holiday = $this->holidayFor($company, $date);
        $schedule = $this->scheduleFor($company);
        $field = self::DAY_FIELDS[$date->isoWeekday()] ?? null;
        $scheduled = $field ? (bool) $schedule->{$field} : false;

        return [
            'date' => $date->toDateString(),
            'is_working_day' => $scheduled && ! $holiday,
            'is_scheduled_workday' => $scheduled,
            'holiday' => $holiday ? [
                'id' => $holiday->id,
                'name' => $holiday->name,
                'type' => $holiday->type,
                'start_date' => $holiday->start_date->toDateString(),
                'end_date' => $holiday->end_date->toDateString(),
                'description' => $holiday->description,
            ] : null,
        ];
    }
}
