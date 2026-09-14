<?php

namespace App\Services;

use App\Models\AssignmentEmployee;
use App\Models\AssignmentLog;
use App\Models\Attendance;
use App\Models\User;
use App\Notifications\AssignmentNotWorked;

class EmployeeAssignmentDeadlineSynchronizer
{
    /**
     * Synchronize expired assignment and revision states when an employee
     * opens assignment-related data, without relying only on a scheduler.
     */
    public function sync(User $user): void
    {
        $employee = $user->employee;

        if (! $employee) {
            return;
        }

        $this->repairLegacyDailyAttendance($employee->id);

        $rows = AssignmentEmployee::query()
            ->with(['assignment', 'employee.user'])
            ->where('employee_id', $employee->id)
            ->whereHas(
                'assignment',
                fn ($assignment) => $assignment->whereIn('status', ['Assigned', 'In Progress', 'Completed'])
            )
            ->where(function ($query): void {
                $query->where('review_status', 'Needs Revision')
                    ->orWhere(function ($active): void {
                        $active->whereNull('review_status')
                            ->whereIn('status', ['Assigned', 'Accepted', 'In Progress']);
                    });
            })
            ->get();

        foreach ($rows as $row) {
            $this->synchronizeRow($row);
        }
    }

    private function repairLegacyDailyAttendance(int $employeeId): void
    {
        $legacyRows = AssignmentEmployee::query()
            ->with('assignment')
            ->where('employee_id', $employeeId)
            ->where('review_status', 'Not Worked')
            ->whereNull('revision_deadline_at')
            ->whereHas('assignment', fn ($query) => $query->where('daily_attendance_enabled', true))
            ->get();

        foreach ($legacyRows as $legacyRow) {
            if (! $this->hasDailyWork($legacyRow->assignment_id, $employeeId)) {
                continue;
            }

            $legacyRow->update([
                'status' => 'Completed',
                'review_status' => 'Pending Review',
                'review_notes' => 'Status diperbaiki otomatis: employee memiliki riwayat kerja Daily Attendance dan menunggu review company.',
                'reviewed_at' => null,
            ]);

            AssignmentLog::create([
                'assignment_id' => $legacyRow->assignment_id,
                'employee_id' => $employeeId,
                'user_id' => null,
                'action' => 'DAILY_ATTENDANCE_STATUS_REPAIRED',
                'description' => 'Status Not Worked lama dikoreksi menjadi Pending Review karena terdapat attendance kerja.',
            ]);
        }
    }

    private function synchronizeRow(AssignmentEmployee $row): void
    {
        $assignment = $row->assignment;
        $revisionExpired = $row->review_status === 'Needs Revision'
            && $row->isPastRevisionGracePeriod();

        $assignmentDeadline = $assignment?->end_datetime?->copy();

        if ($assignmentDeadline && $assignment->daily_attendance_enabled) {
            // Employees may finish the last daily-attendance session until 23:00.
            $assignmentDeadline->setTime(23, 0);
        }

        $assignmentExpired = $row->review_status === null
            && $assignmentDeadline
            && now()->greaterThan($assignmentDeadline);

        if (! $revisionExpired && ! $assignmentExpired) {
            return;
        }

        $hasDailyWork = ! $revisionExpired
            && (bool) $assignment?->daily_attendance_enabled
            && $this->hasDailyWork($row->assignment_id, $row->employee_id);

        if ($hasDailyWork) {
            $this->moveDailyAttendanceToReview($row);

            return;
        }

        $this->markNotWorked($row, $revisionExpired);
    }

    private function moveDailyAttendanceToReview(AssignmentEmployee $row): void
    {
        $row->update([
            'status' => 'Completed',
            'review_status' => 'Pending Review',
            'review_notes' => 'Periode Daily Attendance telah berakhir. Riwayat kerja harian menunggu review company.',
            'reviewed_at' => null,
        ]);

        $stillPending = AssignmentEmployee::query()
            ->where('assignment_id', $row->assignment_id)
            ->whereNotIn('status', ['Completed', 'Cancelled'])
            ->exists();

        if (! $stillPending && in_array($row->assignment->status, ['Assigned', 'In Progress'], true)) {
            $row->assignment->update(['status' => 'Completed']);
        }

        AssignmentLog::create([
            'assignment_id' => $row->assignment_id,
            'employee_id' => $row->employee_id,
            'user_id' => null,
            'action' => 'DAILY_ATTENDANCE_PERIOD_ENDED',
            'description' => 'Periode Daily Attendance berakhir setelah employee pernah bekerja -- otomatis menunggu review company.',
        ]);
    }

    private function markNotWorked(AssignmentEmployee $row, bool $revisionExpired): void
    {
        $row->update([
            'review_status' => 'Not Worked',
            'review_notes' => $revisionExpired
                ? 'Batas waktu revisi telah lewat tanpa submit ulang.'
                : 'Batas waktu assignment telah lewat tanpa pekerjaan yang tercatat.',
            'reviewed_at' => now(),
        ]);

        AssignmentLog::create([
            'assignment_id' => $row->assignment_id,
            'employee_id' => $row->employee_id,
            'user_id' => null,
            'action' => $revisionExpired ? 'REVISION_NOT_WORKED' : 'ASSIGNMENT_NOT_WORKED',
            'description' => $revisionExpired
                ? 'Batas revisi lewat tanpa submit ulang -- otomatis Tidak Dikerjakan.'
                : 'Batas assignment lewat dan tidak ada pekerjaan yang tercatat -- otomatis Tidak Dikerjakan.',
        ]);

        $fresh = $row->fresh(['assignment', 'employee.user']);
        $fresh?->employee?->user?->notify(new AssignmentNotWorked($fresh, $revisionExpired));
    }

    private function hasDailyWork(int $assignmentId, int $employeeId): bool
    {
        return Attendance::query()
            ->where('assignment_id', $assignmentId)
            ->where('employee_id', $employeeId)
            ->where('attendance_type', 'ASSIGNMENT')
            ->where('is_checked_in', true)
            ->exists();
    }
}
