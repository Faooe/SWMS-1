<?php

namespace App\Services;

use App\Models\AssignmentEmployee;
use App\Models\Attendance;
use App\Models\User;

class EmployeeDashboardService
{
    /*
    |--------------------------------------------------------------------------
    | Employee Dashboard
    |--------------------------------------------------------------------------
    */

    public function index(User $user): array
    {
        $employee = $user->employee;

        /*
        |--------------------------------------------------------------------------
        | Today Assignment
        |--------------------------------------------------------------------------
        */

        $todayAssignment = $employee
            ->assignments()
            ->with('office')
            ->whereDate('start_datetime', '<=', today())
            ->whereDate('end_datetime', '>=', today())
            ->wherePivotIn('status', [
                AssignmentEmployee::STATUS_ASSIGNED,
                AssignmentEmployee::STATUS_ACCEPTED,
                AssignmentEmployee::STATUS_IN_PROGRESS,
            ])
            ->orderByPivot('assigned_at', 'desc')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Attendance Today
        |--------------------------------------------------------------------------
        */

        $todayAttendance = Attendance::query()

            ->canonicalDaily()

            ->where(

                'employee_id',

                $employee->id

            )

            ->whereDate(

                'attendance_date',

                today()

            )

            ->first();

        /*
        |--------------------------------------------------------------------------
        | Recent Activities
        |--------------------------------------------------------------------------
        */

        $recentActivities = $employee

            ->assignmentLogs()

            ->latest()

            ->take(5)

            ->get();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $statistics = [

            'assignment' => $employee

                ->assignments()

                ->count(),

            'completed' => $employee

                ->assignments()

                ->wherePivot(

                    'status',

                    AssignmentEmployee::STATUS_COMPLETED

                )

                ->count(),

            'attendance' => Attendance::query()

                ->canonicalDaily()

                ->where(

                    'employee_id',

                    $employee->id

                )

                ->count(),

        ];

        return [

            'employee' => $employee,

            'todayAssignment' => $todayAssignment,

            'todayAttendance' => $todayAttendance,

            'recentActivities' => $recentActivities,

            'statistics' => $statistics,

        ];
    }
}
