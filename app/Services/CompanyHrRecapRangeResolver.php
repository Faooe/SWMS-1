<?php

namespace App\Services;

use App\Models\AssignmentEmployee;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CompanyHrRecapRangeResolver
{
    /** @return array{0: Carbon, 1: Carbon} */
    public function resolve(Request $request): array
    {
        $today = today()->endOfDay();
        $period = (string) $request->query('period', '');

        if ($period === 'day') {
            $day = $this->parseDate($request->query('day') ?? $request->query('from')) ?? $today;
            $from = $day->copy()->startOfDay();
            $to = $day->copy()->endOfDay();
        } elseif ($period === 'month') {
            $fromMonth = $this->parseMonth($request->query('from_month')) ?? $today->copy()->startOfMonth();
            $toMonth = $this->parseMonth($request->query('to_month')) ?? $fromMonth->copy();
            if ($fromMonth->greaterThan($toMonth)) {
                [$fromMonth, $toMonth] = [$toMonth, $fromMonth];
            }
            $from = $fromMonth->copy()->startOfMonth();
            $to = $toMonth->copy()->endOfMonth();
        } elseif ($period === 'year') {
            $fromYear = $this->parseYear($request->query('from_year')) ?? $today->year;
            $toYear = $this->parseYear($request->query('to_year')) ?? $fromYear;
            if ($fromYear > $toYear) {
                [$fromYear, $toYear] = [$toYear, $fromYear];
            }
            $from = Carbon::create($fromYear, 1, 1)->startOfDay();
            $to = Carbon::create($toYear, 12, 31)->endOfDay();
        } elseif ($period === 'all') {
            $from = $this->earliestCompanyDate($request) ?? $today->copy()->startOfYear();
            $to = $today->copy();
        } else {
            // Backward compatible: links/API lama memakai from + to langsung.
            $from = $this->parseDate($request->query('from')) ?? $today->copy()->startOfMonth();
            $to = $this->parseDate($request->query('to')) ?? $today->copy();
        }

        if ($from->greaterThan($to)) {
            [$from, $to] = [$to, $from];
        }

        $to = $to->min($today);

        // Tanggal/bulan/tahun masa depan tidak menghasilkan rentang terbalik
        // setelah batas akhir laporan dikunci ke hari ini.
        if ($from->greaterThan($to)) {
            $from = $to->copy()->startOfDay();
        }

        // Rentang eksplisit bulan/tahun boleh mencakup beberapa tahun.
        // Batas 730 hari hanya dipakai untuk format tanggal lama/umum.
        if (! in_array($period, ['all', 'month', 'year'], true) && $from->diffInDays($to) > 730) {
            $from = $to->copy()->subDays(730);
        }

        return [$from->startOfDay(), $to->endOfDay()];
    }

    public function label(Carbon $from, Carbon $to, string $period): string
    {
        return match ($period) {
            'day' => $from->translatedFormat('l, d F Y'),
            'month' => $from->translatedFormat('F Y').' - '.$to->translatedFormat('F Y'),
            'year' => 'Tahun '.$from->year.' - '.$to->year,
            'all' => 'Semua Data',
            default => $from->translatedFormat('d M Y').' - '.$to->translatedFormat('d M Y'),
        };
    }

    private function parseDate(?string $value): ?Carbon
    {
        if (! $value || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return null;
        }

        try {
            return Carbon::createFromFormat('Y-m-d', $value);
        } catch (\Throwable) {
            return null;
        }
    }

    private function parseMonth(?string $value): ?Carbon
    {
        if (! $value || ! preg_match('/^\d{4}-\d{2}$/', $value)) {
            return null;
        }

        try {
            return Carbon::createFromFormat('!Y-m', $value)->startOfMonth();
        } catch (\Throwable) {
            return null;
        }
    }

    private function parseYear(?string $value): ?int
    {
        if (! $value || ! preg_match('/^\d{4}$/', (string) $value)) {
            return null;
        }

        $year = (int) $value;

        return $year >= 2000 && $year <= today()->year ? $year : null;
    }

    private function earliestCompanyDate(Request $request): ?Carbon
    {
        $companyId = $request->user()?->company_id;
        if (! $companyId) {
            return null;
        }

        $attendanceDate = Attendance::query()
            ->where('company_id', $companyId)
            ->min('attendance_date');
        $assignmentDate = AssignmentEmployee::query()
            ->whereHas('assignment', fn (Builder $query) => $query->where('company_id', $companyId))
            ->min('assigned_at');

        $dates = collect([$attendanceDate, $assignmentDate])
            ->filter()
            ->map(fn ($value) => Carbon::parse($value));

        return $dates->isNotEmpty() ? $dates->sort()->first()->startOfDay() : null;
    }
}
