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

        return $query->select('assignments.*')
            ->selectSub($workRank, 'employee_work_rank')
            ->orderBy('employee_work_rank')
            ->orderByRaw("CASE assignments.priority
                WHEN 'Critical' THEN 0 WHEN 'High' THEN 1
                WHEN 'Medium' THEN 2 WHEN 'Low' THEN 3 ELSE 4 END")
            // Missing deadlines must not precede scheduled work on SQLite/MySQL.
            ->orderByRaw('CASE WHEN assignments.end_datetime IS NULL THEN 1 ELSE 0 END')
            ->orderBy('assignments.end_datetime')
            ->orderByRaw('CASE WHEN assignments.start_datetime IS NULL THEN 1 ELSE 0 END')
            ->orderBy('assignments.start_datetime')
            ->orderByDesc('assignments.created_at')
            ->orderByDesc('assignments.id');
    }
}
