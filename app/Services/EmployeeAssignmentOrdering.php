<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class EmployeeAssignmentOrdering
{
    public static function apply(Builder $query, int $employeeId): Builder
    {
        // Use this employee's workflow, not the global multi-employee status.
        $workRank = DB::table('assignment_employees as my_work')
            ->selectRaw("CASE
                WHEN assignments.status = 'Cancelled'
                    OR my_work.status IN ('Rejected', 'Cancelled')
                    OR my_work.review_status IN ('Not Worked', 'Expired') THEN 3
                WHEN my_work.review_status = 'Approved' THEN 2
                WHEN my_work.review_status = 'Pending Review' THEN 1
                WHEN my_work.review_status = 'Needs Revision' THEN 0
                WHEN my_work.status IN ('Assigned', 'Accepted', 'In Progress') THEN 0
                WHEN my_work.status = 'Completed' THEN 1
                ELSE 3 END")
            ->whereColumn('my_work.assignment_id', 'assignments.id')
            ->where('my_work.employee_id', $employeeId)
            ->limit(1);

        // Repeat the bound subquery: PostgreSQL cannot use a SELECT alias
        // inside a CASE expression in ORDER BY.
        $needsWork = '(' . $workRank->toSql() . ') = 0';
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
