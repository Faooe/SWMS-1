<?php

namespace App\Services\Attendance;

use App\Models\Assignment;
use App\Models\AssignmentEmployee;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Office;

/** Read-only attendance and assignment lookups used by employee workflows. */
class AttendanceLookupService
{
    public function office(Employee $employee): ?Office
    {
        return $employee->currentEmployment?->office;
    }

    public function todayOfficeAttendance(Employee $employee): ?Attendance
    {
        return Attendance::query()
            ->where('employee_id', $employee->id)
            ->office()
            ->today()
            ->latest('id')
            ->first();
    }

    public function todayAssignment(Employee $employee): ?Assignment
    {
        return Assignment::query()
            ->forCurrentCompany()
            ->whereHas('employees', function ($query) use ($employee): void {
                $query
                    ->where('employees.id', $employee->id)
                    ->whereIn('assignment_employees.status', [AssignmentEmployee::STATUS_ASSIGNED, AssignmentEmployee::STATUS_ACCEPTED, AssignmentEmployee::STATUS_IN_PROGRESS])
                    ->where(function ($pivot): void {
                        $pivot->whereNull('assignment_employees.review_status')
                            ->orWhereNotIn('assignment_employees.review_status', [AssignmentEmployee::REVIEW_NOT_WORKED, AssignmentEmployee::REVIEW_EXPIRED]);
                    });
            })
            ->whereDate('start_datetime', '<=', today())
            ->whereDate('end_datetime', '>=', today())
            ->whereIn('status', [Assignment::STATUS_ASSIGNED, Assignment::STATUS_IN_PROGRESS])
            ->orderBy('start_datetime')
            ->first();
    }

    public function todayAssignmentAttendance(
        Employee $employee,
        Assignment $assignment,
    ): ?Attendance {
        return Attendance::query()
            ->where('employee_id', $employee->id)
            ->where('assignment_id', $assignment->id)
            ->assignment()
            ->today()
            ->latest('id')
            ->first();
    }

    public function todayAnyAttendance(Employee $employee): ?Attendance
    {
        return Attendance::query()
            ->canonicalDaily()
            ->where('employee_id', $employee->id)
            ->today()
            ->whereIn('attendance_type', ['OFFICE', 'ASSIGNMENT'])
            ->first();
    }
}
