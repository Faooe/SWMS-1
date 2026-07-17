<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\AttendanceManagementService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(
        protected AttendanceManagementService $attendanceService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Attendance List
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('attendance.index');
    }

    /*
    |--------------------------------------------------------------------------
    | Attendance Detail
    |--------------------------------------------------------------------------
    */

    public function show(int $attendance)
    {
        return view(
            'attendance.show',
            [

                'attendance' => $this->attendanceService
                    ->find($attendance),

            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Export PDF
    |--------------------------------------------------------------------------
    */

    public function exportPdf(Request $request)
    {
        $attendances = $this->attendanceService
            ->getAttendances(

                $request->only([

                    'search',

                    'office',

                    'status',

                    'date',

                    'per_page',

                ])

            );

        $statistics = $this->attendanceService
            ->statistics();

        $pdf = Pdf::loadView(
            'attendance.pdf',
            [

                'attendances' => $attendances,

                'statistics' => $statistics,

            ]
        )->setPaper(
            'a4',
            'landscape'
        );

        return $pdf->download(

            'attendance-report-' .
            now()->format('YmdHis') .
            '.pdf'

        );
    }
}