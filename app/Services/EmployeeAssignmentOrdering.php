<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\AssignmentEmployee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class EmployeeAssignmentOrdering
{
    public static function apply(Builder $query, int $employeeId): Builder
    {
        $statusRejected = AssignmentEmployee::STATUS_REJECTED;
        $statusCancelled = AssignmentEmployee::STATUS_CANCELLED;
        $statusAssigned = AssignmentEmployee::STATUS_ASSIGNED;
        $statusAccepted = AssignmentEmployee::STATUS_ACCEPTED;
        $statusInProgress = AssignmentEmployee::STATUS_IN_PROGRESS;
        $statusCompleted = AssignmentEmployee::STATUS_COMPLETED;
        $reviewNotWorked = AssignmentEmployee::REVIEW_NOT_WORKED;
        $reviewExpired = AssignmentEmployee::REVIEW_EXPIRED;
        $reviewApproved = AssignmentEmployee::REVIEW_APPROVED;
        $reviewPending = AssignmentEmployee::REVIEW_PENDING;
        $reviewNeedsRevision = AssignmentEmployee::REVIEW_NEEDS_REVISION;
        $assignmentCancelled = Assignment::STATUS_CANCELLED;

        // Use this employee's workflow, not the global multi-employee status.
        $workRank = DB::table('assignment_employees as my_work')
            ->selectRaw("CASE
                WHEN assignments.status = '$assignmentCancelled'
                    OR my_work.status IN ('$statusRejected', '$statusCancelled')
                    OR my_work.review_status IN ('$reviewNotWorked', '$reviewExpired') THEN 3
                WHEN my_work.review_status = '$reviewApproved' THEN 2
                WHEN my_work.review_status = '$reviewPending' THEN 1
                WHEN my_work.review_status = '$reviewNeedsRevision' THEN 0
                WHEN my_work.status IN ('$statusAssigned', '$statusAccepted', '$statusInProgress') THEN 0
                WHEN my_work.status = '$statusCompleted' THEN 1
                ELSE 3 END")
            ->whereColumn('my_work.assignment_id', 'assignments.id')
            ->where('my_work.employee_id', $employeeId)
            ->limit(1);

        // Repeat the bound subquery: PostgreSQL cannot use a SELECT alias
        // inside a CASE expression in ORDER BY.
        $needsWork = '('.$workRank->toSql().') = 0';
        $bindings = $workRank->getBindings();

        return $query->select('assignments.*')
            ->selectSub($workRank, 'employee_work_rank')
            ->orderBy('employee_work_rank')
            ->orderByRaw("CASE WHEN {$needsWork} THEN CASE assignments.priority
                WHEN 'Critical' THEN 0 WHEN 'High' THEN 1
                WHEN 'Medium' THEN 2 WHEN 'Low' THEN 3 ELSE 4 END ELSE 0 END", $bindings)
            // Missing deadlines must not precede scheduled work on SQLite/MySQL.
            ->orderByRaw('CASE WHEN assignments.end_datetime IS NULL THEN 1 ELSE 0 END')
            ->orderByRaw("CASE WHEN {$needsWork} THEN assignments.end_datetime END ASC", $bindings)
            ->orderByRaw("CASE WHEN {$needsWork} THEN NULL ELSE assignments.end_datetime END DESC", $bindings)
            ->orderByRaw('CASE WHEN assignments.start_datetime IS NULL THEN 1 ELSE 0 END')
            ->orderByRaw("CASE WHEN {$needsWork} THEN assignments.start_datetime END ASC", $bindings)
            ->orderByRaw("CASE WHEN {$needsWork} THEN NULL ELSE assignments.start_datetime END DESC", $bindings)
            ->orderByDesc('assignments.created_at')
            ->orderByDesc('assignments.id');
    }
}
