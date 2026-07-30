<?php

namespace App\Http\Controllers\Web\Employee;

use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
use App\Services\EmployeeAssignmentService;
use App\Services\EmployeeDashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected EmployeeDashboardService $dashboardService,
        protected AttendanceService $attendanceService,
        protected EmployeeAssignmentService $assignmentService,
    ) {
    }

    /**
     * Employee Dashboard
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return view(
            'employee.dashboard.index',
            [

                // Dashboard Information
                ...$this->dashboardService->index($user),

                // Attendance
                'todayAttendance' => $this->attendanceService->today($user),

                'attendanceStatistics' => $this->attendanceService->statistics($user),

                // Assignment
                'todayAssignments' => $this->assignmentService->today($user),

                'assignmentStatistics' => $this->assignmentService->statistics($user),

                'activeAssignment' => $this->assignmentService->active($user),

            ]
        );
    }
}