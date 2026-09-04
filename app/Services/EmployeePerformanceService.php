<?php

namespace App\Services;

use App\Models\AssignmentEmployee;
use App\Models\Attendance;
use App\Models\Employee;
use App\Services\Attendance\WorkCalendarService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class EmployeePerformanceService
{
    public function __construct(private readonly WorkCalendarService $workCalendar) {}

    /** Resolve HR recap period. Supported: today, month, range, year. */
    public function resolveRecapRange(Request $request): array
    {
        $period = (string) $request->query('period', 'month');
        $now = Carbon::now();

        if ($period === 'today') {
            return [$now->copy()->startOfDay(), $now->copy()->endOfDay(), 'today'];
        }

        if ($period === 'year') {
            $year = max(2000, min(2100, (int) $request->query('year', $now->year)));
            return [Carbon::create($year, 1, 1)->startOfDay(), Carbon::create($year, 12, 31)->endOfDay(), 'year'];
        }

        if ($period === 'range') {
            $from = $this->parseMonth($request->query('from')) ?? $now->copy()->startOfMonth();
            $to = $this->parseMonth($request->query('to')) ?? $from->copy();
            if ($from->greaterThan($to)) [$from, $to] = [$to, $from];
            if ($from->diffInMonths($to) > 23) $from = $to->copy()->subMonths(23);
            return [$from->copy()->startOfMonth(), $to->copy()->endOfMonth(), 'range'];
        }

        $month = $this->parseMonth($request->query('month') ?? $request->query('from'))
            ?? $now->copy()->startOfMonth();
        return [$month->copy()->startOfMonth(), $month->copy()->endOfMonth(), 'month'];
    }

    /** Backward compatible old endpoint callers. */
    public function resolveRange(Request $request): array
    {
        [$start, $end] = $this->resolveRecapRange($request);
        return [$start->copy()->startOfMonth(), $end->copy()->startOfMonth()];
    }

    public function resolveExportRange(Request $request): array
    {
        [$start, $end] = $this->resolveRecapRange($request);
        return [$start, $end];
    }

    private function parseMonth(?string $value): ?Carbon
    {
        if (!$value || !preg_match('/^\d{4}-\d{2}$/', $value)) return null;
        try { return Carbon::createFromFormat('Y-m-d', $value.'-01')->startOfMonth(); }
        catch (\Throwable) { return null; }
    }

    private function attendanceRows(Employee $employee, Carbon $start, Carbon $end): Collection
    {
        return Attendance::query()->canonicalDaily()
            ->where('employee_id', $employee->id)
            ->whereDate('attendance_date', '>=', $start->toDateString())
            ->whereDate('attendance_date', '<=', $end->toDateString())
            ->get();
    }

    public function attendanceSummary(Employee $employee, Carbon $start, Carbon $end): array
    {
        $rows = $this->attendanceRows($employee, $start, $end);
        $effectiveEnd = $end->copy();
        if ($effectiveEnd->isFuture()) $effectiveEnd = Carbon::now()->endOfDay();

        $workingDays = 0;
        if ($start->lte($effectiveEnd)) {
            foreach (CarbonPeriod::create($start->copy()->startOfDay(), '1 day', $effectiveEnd->copy()->startOfDay()) as $day) {
                if ($employee->company && $this->workCalendar->isWorkingDay($employee->company, $day)) $workingDays++;
            }
        }

        $count = fn (string $status) => $rows->where('attendance_status', $status)->count();
        $present = $count('Present');
        $late = $count('Late');
        $leave = $count('Leave');
        $permission = $count('Permission');
        $explicitAbsent = $count('Absent');
        $attended = $present + $late;
        $missing = max(0, $workingDays - $attended - $leave - $permission - $explicitAbsent);
        $absent = $explicitAbsent + $missing;

        $attendanceRate = $workingDays > 0 ? round((($attended + $leave + $permission) / $workingDays) * 100, 1) : 0.0;
        $punctualityRate = $attended > 0 ? round(($present / $attended) * 100, 1) : 0.0;

        return [
            'working_days' => $workingDays,
            'records' => $rows->count(),
            'attended' => $attended,
            'present' => $present,
            'late' => $late,
            'leave' => $leave,
            'permission' => $permission,
            'absent' => $absent,
            'attendance_rate' => $attendanceRate,
            'punctuality_rate' => $punctualityRate,
            'work_minutes' => (int) $rows->sum('work_minutes'),
            'late_minutes' => (int) $rows->sum('late_minutes'),
            'early_leave_minutes' => (int) $rows->sum('early_leave_minutes'),
            'overtime_minutes' => (int) $rows->sum('overtime_minutes'),
        ];
    }

    private function assignmentPivots(Employee $employee, Carbon $start, Carbon $end): Collection
    {
        return AssignmentEmployee::query()
            ->where('employee_id', $employee->id)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('assigned_at', [$start, $end])
                  ->orWhereBetween('finished_at', [$start, $end])
                  ->orWhereBetween('reviewed_at', [$start, $end])
                  ->orWhere(function ($q2) use ($start, $end) {
                      $q2->where('assigned_at', '<=', $end)->whereNull('finished_at');
                  });
            })->get();
    }

    public function assignmentSummary(Employee $employee, Carbon $start, Carbon $end): array
    {
        $rows = $this->assignmentPivots($employee, $start, $end);
        $completed = $rows->where('status', 'Completed')->count();
        $total = $rows->count();
        return [
            'total' => $total,
            'completed' => $completed,
            'in_progress' => $rows->whereIn('status', ['Assigned', 'Accepted', 'In Progress'])->count(),
            'rejected' => $rows->where('status', 'Rejected')->count(),
            'approved' => $rows->where('review_status', 'Approved')->count(),
            'pending_review' => $rows->where('review_status', 'Pending Review')->count(),
            'needs_revision' => $rows->where('review_status', 'Needs Revision')->count(),
            'not_worked' => $rows->whereIn('review_status', ['Not Worked', 'Expired'])->count(),
            'late_revision' => $rows->where('is_late_revision', true)->count(),
            'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 1) : 0.0,
        ];
    }

    private function aggregateAttendance(Employee $employee, Carbon $start, Carbon $end, string $format): array
    {
        $result = [];
        foreach ($this->attendanceRows($employee, $start, $end) as $row) {
            $key = Carbon::parse($row->attendance_date)->format($format);
            $result[$key]['total'] = ($result[$key]['total'] ?? 0) + 1;
            $result[$key]['present'] = ($result[$key]['present'] ?? 0) + ($row->attendance_status === 'Present' ? 1 : 0);
            $result[$key]['late'] = ($result[$key]['late'] ?? 0) + ($row->attendance_status === 'Late' ? 1 : 0);
        }
        return $result;
    }

    private function aggregateAssignments(Employee $employee, Carbon $start, Carbon $end, string $format): array
    {
        $rows = AssignmentEmployee::query()->where('employee_id', $employee->id)
            ->where('status', 'Completed')->whereBetween('finished_at', [$start, $end])->get();
        $result = [];
        foreach ($rows as $row) {
            $key = Carbon::parse($row->finished_at)->format($format);
            $result[$key] = ($result[$key] ?? 0) + 1;
        }
        return $result;
    }

    public function chartData(Employee $employee, Carbon $start, Carbon $end): array
    {
        $days = $start->copy()->startOfDay()->diffInDays($end->copy()->startOfDay());
        if ($days <= 31) {
            $attendance = $this->aggregateAttendance($employee, $start, $end, 'Y-m-d');
            $assignments = $this->aggregateAssignments($employee, $start, $end, 'Y-m-d');
            $points = collect(CarbonPeriod::create($start->copy()->startOfDay(), '1 day', $end->copy()->startOfDay()))
                ->map(function (Carbon $day) use ($attendance, $assignments) {
                    $key = $day->format('Y-m-d'); $a = $attendance[$key] ?? ['total'=>0,'present'=>0,'late'=>0];
                    return ['date'=>$key,'label'=>$day->translatedFormat('d M'),'attendance_total'=>$a['total'],'attendance_present'=>$a['present'],'attendance_late'=>$a['late'],'assignment_completed'=>$assignments[$key] ?? 0];
                })->values()->all();
            return ['granularity'=>'daily','points'=>$points];
        }

        $attendance = $this->aggregateAttendance($employee, $start, $end, 'Y-m');
        $assignments = $this->aggregateAssignments($employee, $start, $end, 'Y-m');
        $first = $start->copy()->startOfMonth(); $last = $end->copy()->startOfMonth();
        $points = collect(CarbonPeriod::create($first, '1 month', $last))->map(function (Carbon $month) use ($attendance,$assignments) {
            $key=$month->format('Y-m'); $a=$attendance[$key] ?? ['total'=>0,'present'=>0,'late'=>0];
            return ['year'=>$month->year,'month'=>$month->month,'label'=>$month->translatedFormat('M Y'),'attendance_total'=>$a['total'],'attendance_present'=>$a['present'],'attendance_late'=>$a['late'],'assignment_completed'=>$assignments[$key] ?? 0];
        })->values()->all();
        return ['granularity'=>'monthly','points'=>$points];
    }

    // Legacy helpers retained for web/export compatibility.
    public function monthlyChart(Employee $employee, Carbon $from, Carbon $to): array { return $this->chartData($employee, $from->copy()->startOfMonth(), $to->copy()->endOfMonth())['points']; }
    public function dailyChart(Employee $employee, Carbon $month): array { return $this->chartData($employee, $month->copy()->startOfMonth(), $month->copy()->endOfMonth())['points']; }
    public function summary(array $chart): array { $r=collect($chart); return ['attendance_total'=>$r->sum('attendance_total'),'attendance_present'=>$r->sum('attendance_present'),'attendance_late'=>$r->sum('attendance_late'),'assignment_completed'=>$r->sum('assignment_completed')]; }
    public function reviewSummary(Employee $employee, Carbon $from, Carbon $to): array { $s=$this->assignmentSummary($employee,$from,$to); return ['approved'=>$s['approved'],'pending_review'=>$s['pending_review'],'needs_revision'=>$s['needs_revision'],'expired'=>$s['not_worked'],'late_revision_count'=>$s['late_revision'],'rejected'=>$s['rejected']]; }

    public function attendanceDetail(Employee $employee, Carbon $from, Carbon $to): Collection
    {
        return Attendance::query()->canonicalDaily()->where('employee_id',$employee->id)
            ->whereDate('attendance_date','>=',$from->toDateString())->whereDate('attendance_date','<=',$to->toDateString())
            ->with(['office'])->orderBy('attendance_date')->orderBy('check_in_time')->get();
    }

    public function assignmentDetail(Employee $employee, Carbon $from, Carbon $to): Collection
    {
        return $employee->assignments()->wherePivot('status','Completed')
            ->wherePivot('finished_at','>=',$from)->wherePivot('finished_at','<=',$to)
            ->orderByPivot('finished_at')->get();
    }
}
