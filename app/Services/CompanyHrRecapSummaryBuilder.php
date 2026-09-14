<?php

namespace App\Services;

use Illuminate\Support\Collection;

/**
 * Builds the company-level totals shown alongside the per-employee recap.
 * Keeping this calculation separate makes the aggregation independently
 * testable and prevents the orchestration service from growing further.
 */
class CompanyHrRecapSummaryBuilder
{
    public function build(Collection $rows): array
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
}
