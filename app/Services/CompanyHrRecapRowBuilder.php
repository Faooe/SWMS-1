<?php

namespace App\Services;

use App\Models\Employee;

class CompanyHrRecapRowBuilder
{
    public function build(
        Employee $employee,
        int $employeeWorkingDays,
        ?object $attendanceRow,
        ?object $assignmentRow,
    ): array {
        $employment = $employee->currentEmployment;

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
    }
}
