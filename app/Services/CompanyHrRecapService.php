<?php

namespace App\Services;

use App\Models\AssignmentEmployee;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\Employee;
use App\Services\Attendance\WorkCalendarService;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CompanyHrRecapService
{
    private const PER_PAGE = 10;

    public function __construct(private readonly WorkCalendarService $workCalendar) {}

    /** @return array{0: Carbon, 1: Carbon} */
    public function resolveRange(Request $request): array
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

    /**
     * @return array{
     *   range: array{from:string,to:string,label:string,working_days:int},
     *   summary: array<string,int|float>,
     *   rows: Collection|LengthAwarePaginatorContract,
     *   employee_ids: Collection<int,int>
     * }
     */
    public function recap(Company $company, Request $request, bool $paginate = true): array
    {
        [$from, $to] = $this->resolveRange($request);
        $period = (string) $request->query('period', '');
        $employees = $this->employeeQuery($company, $request)->get();
        $employeeIds = $employees->pluck('id')->values();
        $workingDates = $this->workCalendar->workingDatesBetween($company, $from, $to);
        $attendance = $this->attendanceAggregates($company, $employeeIds, $from, $to);
        $assignments = $this->assignmentAggregates($company, $employeeIds, $from, $to);

        $rows = $employees->map(function (Employee $employee) use ($workingDates, $attendance, $assignments): array {
            $attendanceRow = $attendance->get($employee->id);
            $assignmentRow = $assignments->get($employee->id);
            $employment = $employee->currentEmployment;
            $employeeWorkingDays = $this->employeeWorkingDays($workingDates, $employment?->start_date, $employment?->end_date);

            $present = (int) ($attendanceRow?->present ?? 0);
            $late = (int) ($attendanceRow?->late ?? 0);
            $leave = (int) ($attendanceRow?->leave_count ?? 0);
            $permission = (int) ($attendanceRow?->permission_count ?? 0);
            $explicitAbsent = (int) ($attendanceRow?->absent ?? 0);
            $attended = $present + $late;
            $missing = max(0, $employeeWorkingDays - $attended - $leave - $permission - $explicitAbsent);
            $absent = $explicitAbsent + $missing;
            $attendanceRate = $employeeWorkingDays > 0
                ? round((($attended + $leave + $permission) / $employeeWorkingDays) * 100, 1)
                : 0.0;

            $assignmentTotal = (int) ($assignmentRow?->total ?? 0);
            $assignmentCompleted = (int) ($assignmentRow?->completed ?? 0);
            $completionRate = $assignmentTotal > 0
                ? round(($assignmentCompleted / $assignmentTotal) * 100, 1)
                : 0.0;

            return [
                'employee_id' => $employee->id,
                'employee_number' => $employee->employee_number,
                'employee_name' => $employee->full_name,
                'employee_photo_url' => $employee->photo ? secure_file_url($employee->photo) : null,
                'is_active' => (bool) $employee->is_active,
                'department_id' => $employment?->department_id,
                'department' => $employment?->department?->name ?? '-',
                'position_id' => $employment?->position_id,
                'position' => $employment?->position?->name ?? '-',
                'team_id' => $employment?->team_id,
                'team' => $employment?->team?->name ?? '-',
                'office_id' => $employment?->office_id,
                'office' => $employment?->office?->name ?? '-',
                'working_days' => $employeeWorkingDays,
                'attendance_records' => (int) ($attendanceRow?->records ?? 0),
                'attended' => $attended,
                'present' => $present,
                'late' => $late,
                'leave' => $leave,
                'permission' => $permission,
                'absent' => $absent,
                'work_minutes' => (int) ($attendanceRow?->work_minutes ?? 0),
                'late_minutes' => (int) ($attendanceRow?->late_minutes ?? 0),
                'overtime_minutes' => (int) ($attendanceRow?->overtime_minutes ?? 0),
                'attendance_rate' => $attendanceRate,
                'assignment_total' => $assignmentTotal,
                'assignment_completed' => $assignmentCompleted,
                'assignment_in_progress' => (int) ($assignmentRow?->in_progress ?? 0),
                'assignment_rejected' => (int) ($assignmentRow?->rejected ?? 0),
                'assignment_approved' => (int) ($assignmentRow?->approved ?? 0),
                'assignment_pending_review' => (int) ($assignmentRow?->pending_review ?? 0),
                'assignment_needs_revision' => (int) ($assignmentRow?->needs_revision ?? 0),
                'assignment_not_worked' => (int) ($assignmentRow?->not_worked ?? 0),
                'assignment_late_revision' => (int) ($assignmentRow?->late_revision ?? 0),
                'completion_rate' => $completionRate,
                'performance_score' => round(($attendanceRate * 0.6) + ($completionRate * 0.4), 1),
            ];
        });

        $rows = $this->sortRows($rows, (string) $request->query('sort', 'name'));
        $summary = $this->summary($rows);

        return [
            'range' => [
                'from' => $from->toDateString(),
                'to' => $to->toDateString(),
                'period' => in_array($period, ['day', 'month', 'year', 'all'], true) ? $period : 'range',
                'label' => $this->rangeLabel($from, $to, $period),
                'working_days' => $workingDates->count(),
            ],
            'summary' => $summary,
            'rows' => $paginate ? $this->paginate($rows, $request) : $rows->values(),
            'employee_ids' => $employeeIds,
        ];
    }

    public function attendanceDetails(Company $company, Collection $employeeIds, Carbon $from, Carbon $to): Collection
    {
        if ($employeeIds->isEmpty()) {
            return collect();
        }

        return Attendance::query()
            ->canonicalDaily()
            ->where('company_id', $company->id)
            ->whereIn('employee_id', $employeeIds)
            ->whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()])
            ->with(['employee:id,employee_number,full_name', 'office:id,name'])
            ->orderBy('attendance_date')
            ->orderBy('employee_id')
            ->get();
    }

    public function assignmentDetails(Company $company, Collection $employeeIds, Carbon $from, Carbon $to): Collection
    {
        if ($employeeIds->isEmpty()) {
            return collect();
        }

        return AssignmentEmployee::query()
            ->whereIn('employee_id', $employeeIds)
            ->whereHas('assignment', fn (Builder $query) => $query->where('company_id', $company->id))
            ->where(fn (Builder $query) => $this->applyAssignmentRange($query, $from, $to))
            ->with([
                'employee:id,employee_number,full_name',
                'assignment:id,assignment_number,title,priority,assignment_type,start_datetime,end_datetime',
            ])
            ->orderBy('assigned_at')
            ->orderBy('employee_id')
            ->get();
    }

    private function employeeQuery(Company $company, Request $request): Builder
    {
        $query = Employee::query()
            ->where('company_id', $company->id)
            ->with([
                'currentEmployment.department:id,name',
                'currentEmployment.position:id,name',
                'currentEmployment.team:id,name',
                'currentEmployment.office:id,name',
            ]);

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where(function (Builder $builder) use ($search): void {
                $builder->where('full_name', 'ILIKE', "%{$search}%")
                    ->orWhere('employee_number', 'ILIKE', "%{$search}%");
            });
        }

        foreach (['office_id', 'department_id', 'position_id', 'team_id'] as $field) {
            if ($request->filled($field)) {
                $query->whereHas('currentEmployment', fn (Builder $employment) => $employment->where($field, $request->integer($field)));
            }
        }

        if (in_array((string) $request->query('active'), ['0', '1'], true)) {
            $query->where('is_active', $request->boolean('active'));
        }

        return $query;
    }

    private function attendanceAggregates(
        Company $company,
        Collection $employeeIds,
        Carbon $from,
        Carbon $to,
    ): Collection {
        if ($employeeIds->isEmpty()) {
            return collect();
        }

        return Attendance::query()
            ->canonicalDaily()
            ->where('company_id', $company->id)
            ->whereIn('employee_id', $employeeIds)
            ->whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()])
            ->select('employee_id')
            ->selectRaw('COUNT(*) AS records')
            ->selectRaw("SUM(CASE WHEN attendance_status = 'Present' THEN 1 ELSE 0 END) AS present")
            ->selectRaw("SUM(CASE WHEN attendance_status = 'Late' THEN 1 ELSE 0 END) AS late")
            ->selectRaw("SUM(CASE WHEN attendance_status = 'Leave' THEN 1 ELSE 0 END) AS leave_count")
            ->selectRaw("SUM(CASE WHEN attendance_status = 'Permission' THEN 1 ELSE 0 END) AS permission_count")
            ->selectRaw("SUM(CASE WHEN attendance_status = 'Absent' THEN 1 ELSE 0 END) AS absent")
            ->selectRaw('COALESCE(SUM(work_minutes), 0) AS work_minutes')
            ->selectRaw('COALESCE(SUM(late_minutes), 0) AS late_minutes')
            ->selectRaw('COALESCE(SUM(overtime_minutes), 0) AS overtime_minutes')
            ->groupBy('employee_id')
            ->get()
            ->keyBy('employee_id');
    }

    private function assignmentAggregates(
        Company $company,
        Collection $employeeIds,
        Carbon $from,
        Carbon $to,
    ): Collection {
        if ($employeeIds->isEmpty()) {
            return collect();
        }

        return AssignmentEmployee::query()
            ->join('assignments', 'assignments.id', '=', 'assignment_employees.assignment_id')
            ->where('assignments.company_id', $company->id)
            ->whereNull('assignments.deleted_at')
            ->whereIn('assignment_employees.employee_id', $employeeIds)
            ->where(fn (Builder $query) => $this->applyAssignmentRange($query, $from, $to))
            ->select('assignment_employees.employee_id')
            ->selectRaw('COUNT(*) AS total')
            ->selectRaw("SUM(CASE WHEN assignment_employees.status = 'Completed' THEN 1 ELSE 0 END) AS completed")
            ->selectRaw("SUM(CASE WHEN assignment_employees.status IN ('Assigned', 'Accepted', 'In Progress') THEN 1 ELSE 0 END) AS in_progress")
            ->selectRaw("SUM(CASE WHEN assignment_employees.status = 'Rejected' THEN 1 ELSE 0 END) AS rejected")
            ->selectRaw("SUM(CASE WHEN assignment_employees.review_status = 'Approved' THEN 1 ELSE 0 END) AS approved")
            ->selectRaw("SUM(CASE WHEN assignment_employees.review_status = 'Pending Review' THEN 1 ELSE 0 END) AS pending_review")
            ->selectRaw("SUM(CASE WHEN assignment_employees.review_status = 'Needs Revision' THEN 1 ELSE 0 END) AS needs_revision")
            ->selectRaw("SUM(CASE WHEN assignment_employees.review_status IN ('Not Worked', 'Expired') THEN 1 ELSE 0 END) AS not_worked")
            ->selectRaw('SUM(CASE WHEN assignment_employees.is_late_revision = true THEN 1 ELSE 0 END) AS late_revision')
            ->groupBy('assignment_employees.employee_id')
            ->get()
            ->keyBy('employee_id');
    }

    private function applyAssignmentRange(Builder $query, Carbon $from, Carbon $to): void
    {
        $query->whereBetween('assignment_employees.assigned_at', [$from, $to])
            ->orWhereBetween('assignment_employees.finished_at', [$from, $to])
            ->orWhereBetween('assignment_employees.reviewed_at', [$from, $to])
            ->orWhere(function (Builder $active) use ($to): void {
                $active->where('assignment_employees.assigned_at', '<=', $to)
                    ->whereNull('assignment_employees.finished_at');
            });
    }

    private function employeeWorkingDays(Collection $dates, mixed $startDate, mixed $endDate): int
    {
        if (! $startDate && ! $endDate) {
            return $dates->count();
        }

        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : null;

        return $dates->filter(function (CarbonInterface $date) use ($start, $end): bool {
            return (! $start || $date->greaterThanOrEqualTo($start))
                && (! $end || $date->lessThanOrEqualTo($end));
        })->count();
    }

    private function summary(Collection $rows): array
    {
        $employeeDays = (int) $rows->sum('working_days');
        $attended = (int) $rows->sum('attended');
        $leave = (int) $rows->sum('leave');
        $permission = (int) $rows->sum('permission');
        $assignmentTotal = (int) $rows->sum('assignment_total');
        $assignmentCompleted = (int) $rows->sum('assignment_completed');

        return [
            'employees' => $rows->count(),
            'active_employees' => $rows->where('is_active', true)->count(),
            'employee_working_days' => $employeeDays,
            'attendance_records' => (int) $rows->sum('attendance_records'),
            'attended' => $attended,
            'present' => (int) $rows->sum('present'),
            'late' => (int) $rows->sum('late'),
            'leave' => $leave,
            'permission' => $permission,
            'absent' => (int) $rows->sum('absent'),
            'attendance_rate' => $employeeDays > 0
                ? round((($attended + $leave + $permission) / $employeeDays) * 100, 1)
                : 0.0,
            'assignment_total' => $assignmentTotal,
            'assignment_completed' => $assignmentCompleted,
            'assignment_in_progress' => (int) $rows->sum('assignment_in_progress'),
            'assignment_rejected' => (int) $rows->sum('assignment_rejected'),
            'assignment_not_worked' => (int) $rows->sum('assignment_not_worked'),
            'assignment_pending_review' => (int) $rows->sum('assignment_pending_review'),
            'assignment_needs_revision' => (int) $rows->sum('assignment_needs_revision'),
            'completion_rate' => $assignmentTotal > 0
                ? round(($assignmentCompleted / $assignmentTotal) * 100, 1)
                : 0.0,
        ];
    }

    private function sortRows(Collection $rows, string $sort): Collection
    {
        return (match ($sort) {
            'attendance_low' => $rows->sortBy([['attendance_rate', 'asc'], ['employee_name', 'asc']]),
            'absent_high' => $rows->sortBy([['absent', 'desc'], ['employee_name', 'asc']]),
            'completion_low' => $rows->sortBy([['completion_rate', 'asc'], ['employee_name', 'asc']]),
            'not_worked_high' => $rows->sortBy([['assignment_not_worked', 'desc'], ['employee_name', 'asc']]),
            default => $rows->sortBy('employee_name', SORT_NATURAL | SORT_FLAG_CASE),
        })->values();
    }

    private function paginate(Collection $rows, Request $request): LengthAwarePaginator
    {
        $page = max(1, $request->integer('page', 1));

        return new LengthAwarePaginator(
            $rows->forPage($page, self::PER_PAGE)->values(),
            $rows->count(),
            self::PER_PAGE,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ],
        );
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

    private function rangeLabel(Carbon $from, Carbon $to, string $period): string
    {
        return match ($period) {
            'day' => $from->translatedFormat('l, d F Y'),
            'month' => $from->translatedFormat('F Y').' - '.$to->translatedFormat('F Y'),
            'year' => 'Tahun '.$from->year.' - '.$to->year,
            'all' => 'Semua Data',
            default => $from->translatedFormat('d M Y').' - '.$to->translatedFormat('d M Y'),
        };
    }
}
