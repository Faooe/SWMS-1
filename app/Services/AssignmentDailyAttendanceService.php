<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\AttendanceCheckoutCorrection;
use App\Models\Employee;
use App\Services\Attendance\WorkCalendarService;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AssignmentDailyAttendanceService
{
    private const DAY_FIELDS = [
        1 => 'monday',
        2 => 'tuesday',
        3 => 'wednesday',
        4 => 'thursday',
        5 => 'friday',
        6 => 'saturday',
        7 => 'sunday',
    ];

    public function __construct(
        protected WorkCalendarService $workCalendarService
    ) {
    }

    /**
     * Build Daily Attendance state for all supplied employees in one batch.
     *
     * @return array<int, array{calendar: array<int,array<string,mixed>>, summary: array<string,int|float>}>
     */
    public function build(Assignment $assignment, Collection $employees): array
    {
        if (!$assignment->daily_attendance_enabled || $employees->isEmpty()) {
            return [];
        }

        $assignment->loadMissing('company');
        $company = $assignment->company;

        if (!$company || !$assignment->start_datetime || !$assignment->end_datetime) {
            return [];
        }

        $employeeIds = $employees->pluck('id')->map(fn ($id) => (int) $id)->values();
        $start = $assignment->start_datetime->copy()->startOfDay();
        $end = $assignment->end_datetime->copy()->startOfDay();

        $attendanceRecords = Attendance::query()
            ->where('assignment_id', $assignment->id)
            ->where('attendance_type', 'ASSIGNMENT')
            ->whereIn('employee_id', $employeeIds)
            ->whereBetween('attendance_date', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->orderBy('id')
            ->get();

        $recordsByEmployee = $attendanceRecords
            ->groupBy('employee_id')
            ->map(fn (Collection $rows) => $rows->keyBy(fn (Attendance $attendance) => $attendance->attendance_date->toDateString()));

        $correctionsByAttendance = AttendanceCheckoutCorrection::query()
            ->whereIn('attendance_id', $attendanceRecords->pluck('id')->filter()->values())
            ->latest('id')
            ->get()
            ->groupBy('attendance_id');

        // Load the work schedule and all holidays once for the whole assignment
        // instead of querying them again for every employee/date row.
        $schedule = $this->workCalendarService->scheduleFor($company);
        $holidays = \App\Models\CompanyHoliday::query()
            ->where('company_id', $company->id)
            ->whereDate('start_date', '<=', $end)
            ->whereDate('end_date', '>=', $start)
            ->get();

        $result = [];

        foreach ($employees as $employee) {
            /** @var Employee $employee */
            $records = $recordsByEmployee->get($employee->id, collect());
            $rows = [];
            $cursor = $start->copy();

            while ($cursor->lte($end)) {
                $date = $cursor->toDateString();
                $required = $assignment->attendance_day_rule === 'EVERY_DAY'
                    || $this->isWorkingDay($cursor, $schedule, $holidays);

                /** @var Attendance|null $attendance */
                $attendance = $records->get($date);
                $isPast = $cursor->lt(today());
                $isToday = $cursor->isSameDay(today());

                $status = 'UPCOMING';
                if (!$required) {
                    $status = 'OFF';
                } elseif ($attendance?->is_checked_out) {
                    $status = $attendance->attendance_status === 'Late' ? 'LATE' : 'PRESENT';
                } elseif ($attendance?->is_checked_in) {
                    $status = $isPast ? 'INCOMPLETE' : 'WORKING';
                } elseif ($isPast) {
                    $status = 'ABSENT';
                } elseif ($isToday) {
                    $status = 'TODAY';
                }

                $latestCorrection = $attendance
                    ? $correctionsByAttendance->get($attendance->id, collect())->first()
                    : null;

                $rows[] = [
                    'date' => $date,
                    'required' => $required,
                    'status' => $status,
                    'attendance_status' => $attendance?->attendance_status,
                    'check_in' => optional($attendance?->check_in_time)->format('H:i'),
                    'check_out' => optional($attendance?->check_out_time)->format('H:i'),
                    'checked_in' => (bool) ($attendance?->is_checked_in),
                    'checked_out' => (bool) ($attendance?->is_checked_out),
                    'late_minutes' => (int) ($attendance?->late_minutes ?? 0),
                    'work_minutes' => (int) ($attendance?->work_minutes ?? 0),
                    'early_leave_minutes' => (int) ($attendance?->early_leave_minutes ?? 0),
                    'overtime_minutes' => (int) ($attendance?->overtime_minutes ?? 0),
                    'checkout_correction' => $latestCorrection
                        ? $this->correctionPayload($latestCorrection, $attendance)
                        : null,
                ];

                $cursor->addDay();
            }

            $result[(int) $employee->id] = [
                'calendar' => $rows,
                'summary' => $this->summary($rows),
            ];
        }

        return $result;
    }

    /**
     * @return array{calendar: array<int,array<string,mixed>>, summary: array<string,int|float>}
     */
    public function forEmployee(Assignment $assignment, Employee $employee): array
    {
        return $this->build($assignment, collect([$employee]))[(int) $employee->id]
            ?? ['calendar' => [], 'summary' => $this->summary([])];
    }

    private function isWorkingDay(Carbon $date, $schedule, Collection $holidays): bool
    {
        $isHoliday = $holidays->contains(function ($holiday) use ($date) {
            return $date->betweenIncluded(
                Carbon::parse($holiday->start_date)->startOfDay(),
                Carbon::parse($holiday->end_date)->startOfDay()
            );
        });

        if ($isHoliday) {
            return false;
        }

        $field = self::DAY_FIELDS[$date->isoWeekday()] ?? null;

        return $field ? (bool) $schedule->{$field} : false;
    }

    /** @param array<int,array<string,mixed>> $rows */
    private function summary(array $rows): array
    {
        $collection = collect($rows);
        $required = $collection->where('required', true);
        $attended = $required->where('checked_in', true)->count();
        $completed = $required->where('checked_out', true)->count();
        $total = $required->count();

        return [
            'required_days' => $total,
            'attended_days' => $attended,
            'completed_days' => $completed,
            'absent_days' => $required->where('status', 'ABSENT')->count(),
            'incomplete_days' => $required->where('status', 'INCOMPLETE')->count(),
            'late_days' => $required->where('status', 'LATE')->count(),
            'attendance_rate' => $total > 0 ? round(($attended / $total) * 100, 1) : 0,
            'work_minutes' => (int) $collection->sum('work_minutes'),
            'early_leave_minutes' => (int) $collection->sum('early_leave_minutes'),
            'overtime_minutes' => (int) $collection->sum('overtime_minutes'),
        ];
    }

    private function correctionPayload(AttendanceCheckoutCorrection $correction, Attendance $attendance): array
    {
        return [
            'id' => $correction->id,
            'uuid' => $correction->uuid,
            'attendance_id' => $correction->attendance_id,
            'employee_id' => $correction->employee_id,
            'attendance_date' => optional($attendance->attendance_date)->toDateString(),
            'requested_check_out_time' => substr((string) $correction->requested_check_out_time, 0, 5),
            'reason' => $correction->reason,
            'status' => $correction->status,
            'review_notes' => $correction->review_notes,
            'reviewed_at' => optional($correction->reviewed_at)->format('Y-m-d H:i:s'),
            'created_at' => optional($correction->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
