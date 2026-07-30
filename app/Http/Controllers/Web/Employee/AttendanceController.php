<?php

namespace App\Http\Controllers\Web\Employee;

use App\Http\Controllers\Controller;
use App\Services\Attendance\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct(
        protected AttendanceService $attendanceService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard Attendance
    |--------------------------------------------------------------------------
    */

    public function index()
    {

        $employee = Auth::user()->employee;

        $office = $this->attendanceService->getOffice($employee);

        $officeAttendance = $this->attendanceService

            ->getTodayOfficeAttendance($employee);

        $assignment = $this->attendanceService

            ->getTodayAssignment($employee);

        $assignmentAttendance = $assignment

            ? $this->attendanceService->getTodayAssignmentAttendance(
                $employee,
                $assignment
            )

            : null;

        $summary = $this->attendanceService

            ->getMonthlySummary($employee);

        return view('employee.attendance.index', [

            'office' => $office,

            'officeAttendance' => $officeAttendance,

            'assignment' => $assignment,

            'assignmentAttendance' => $assignmentAttendance,

            'summary' => $summary,

        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Check In (Office)
    |--------------------------------------------------------------------------
    */

    public function checkIn(Request $request)
    {

        $request->validate([

            'latitude' => ['required', 'numeric'],

            'longitude' => ['required', 'numeric'],

        ]);

        $employee = Auth::user()->employee;

        $result = $this->attendanceService->checkInOffice(

            $employee,

            (float) $request->latitude,

            (float) $request->longitude

        );

        return response()->json(

            $result,

            $result['success'] ? 200 : 422

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Check Out (Office)
    |--------------------------------------------------------------------------
    */

    public function checkOut(Request $request)
    {

        $request->validate([

            'latitude' => ['required', 'numeric'],

            'longitude' => ['required', 'numeric'],

        ]);

        $employee = Auth::user()->employee;

        $result = $this->attendanceService->checkOutOffice(

            $employee,

            (float) $request->latitude,

            (float) $request->longitude

        );

        return response()->json(

            $result,

            $result['success'] ? 200 : 422

        );

    }

    /*
    |--------------------------------------------------------------------------
    | History
    |--------------------------------------------------------------------------
    */

    public function history(Request $request)
    {

        $employee = Auth::user()->employee;

        $history = $this->attendanceService->getHistory(

            $employee,

            $request->only([
                'month',
                'status',
                'type',
                'per_page',
            ])

        );

        return view('employee.attendance.history', [

            'history' => $history,

        ]);

    }
}