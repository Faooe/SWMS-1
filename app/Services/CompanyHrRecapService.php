<?php

namespace App\Services;

use App\Models\AssignmentEmployee;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\Employee;
use App\Services\Attendance\WorkCalendarService;
use App\Support\CaseInsensitiveSearch;
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

    public function __construct(
        private readonly WorkCalendarService $workCalendar,
        private readonly CompanyHrRecapRangeResolver $rangeResolver,
        private readonly CompanyHrRecapRowBuilder $rowBuilder,
        private readonly CompanyHrRecapSummaryBuilder $summaryBuilder,
    ) {}

    /** @return array{0: Carbon, 1: Carbon} */
    public function resolveRange(Request $request): array
    {
        return $this->rangeResolver->resolve($request);
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

            return $this->rowBuilder->build($employee, $employeeWorkingDays, $attendanceRow, $assignmentRow);
        });

        $rows = $this->sortRows($rows, (string) $request->query('sort', 'name'));
        $summary = $this->summaryBuilder->build($rows);

        return [
            'range' => [
                'from' => $from->toDateString(),
                'to' => $to->toDateString(),
                'period' => in_array($period, ['day', 'month', 'year', 'all'], true) ? $period : 'range',
                'label' => $this->rangeResolver->label($from, $to, $period),
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
            CaseInsensitiveSearch::contains($query, (string) $request->query('search'), ['full_name', 'employee_number']);
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
            ->selectRaw('SUM(CASE WHEN attendance_status = ? THEN 1 ELSE 0 END) AS present', [Attendance::STATUS_PRESENT])
            ->selectRaw('SUM(CASE WHEN attendance_status = ? THEN 1 ELSE 0 END) AS late', [Attendance::STATUS_LATE])
            ->selectRaw('SUM(CASE WHEN attendance_status = ? THEN 1 ELSE 0 END) AS leave_count', [Attendance::STATUS_LEAVE])
            ->selectRaw('SUM(CASE WHEN attendance_status = ? THEN 1 ELSE 0 END) AS permission_count', [Attendance::STATUS_PERMISSION])
            ->selectRaw('SUM(CASE WHEN attendance_status = ? THEN 1 ELSE 0 END) AS absent', [Attendance::STATUS_ABSENT])
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
            ->selectRaw('SUM(CASE WHEN assignment_employees.status = ? THEN 1 ELSE 0 END) AS completed', [AssignmentEmployee::STATUS_COMPLETED])
            ->selectRaw('SUM(CASE WHEN assignment_employees.status IN (?, ?, ?) THEN 1 ELSE 0 END) AS in_progress', [
                AssignmentEmployee::STATUS_ASSIGNED,
                AssignmentEmployee::STATUS_ACCEPTED,
                AssignmentEmployee::STATUS_IN_PROGRESS,
            ])
            ->selectRaw('SUM(CASE WHEN assignment_employees.status = ? THEN 1 ELSE 0 END) AS rejected', [AssignmentEmployee::STATUS_REJECTED])
            ->selectRaw('SUM(CASE WHEN assignment_employees.review_status = ? THEN 1 ELSE 0 END) AS approved', [AssignmentEmployee::REVIEW_APPROVED])
            ->selectRaw('SUM(CASE WHEN assignment_employees.review_status = ? THEN 1 ELSE 0 END) AS pending_review', [AssignmentEmployee::REVIEW_PENDING])
            ->selectRaw('SUM(CASE WHEN assignment_employees.review_status = ? THEN 1 ELSE 0 END) AS needs_revision', [AssignmentEmployee::REVIEW_NEEDS_REVISION])
            ->selectRaw('SUM(CASE WHEN assignment_employees.review_status IN (?, ?) THEN 1 ELSE 0 END) AS not_worked', [
                AssignmentEmployee::REVIEW_NOT_WORKED,
                AssignmentEmployee::REVIEW_EXPIRED,
            ])
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
}
