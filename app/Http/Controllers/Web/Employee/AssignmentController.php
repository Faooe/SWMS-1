<?php

namespace App\Http\Controllers\Web\Employee;

use App\Http\Controllers\Controller;
use App\Services\Attendance\AttendanceService;
use App\Services\EmployeeAssignmentService;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function __construct(
        protected EmployeeAssignmentService $assignmentService,
        protected AttendanceService $attendanceService
    ) {
    }

    /**
     * Assignment List
     */
    public function index(Request $request)
    {
        return view(
            'employee.assignments.index',
            [

                'assignments' => $this->assignmentService->getAssignments(

                    $request->user(),

                    $request->only([
                        'search',
                        'status',
                        'priority',
                        'per_page',
                    ])

                ),

                'statistics' => $this->assignmentService
                    ->statistics($request->user()),

            ]
        );
    }

    /**
     * Assignment Detail
     */
    public function show(
        Request $request,
        string $uuid
    ) {

        return view(
            'employee.assignments.show',
            [

                'assignment' => $this->assignmentService
                    ->find(
                        $request->user(),
                        $uuid
                    ),

            ]
        );

    }

    /**
     * Accept Assignment
     */
    public function accept(
        Request $request,
        string $uuid
    ) {

        $this->assignmentService->accept(

            $request->user(),

            $uuid

        );

        return back()->with(

            'success',

            'Assignment berhasil diterima.'

        );

    }

    /**
     * Reject Assignment
     */
    public function reject(
        Request $request,
        string $uuid
    ) {

        $this->assignmentService->reject(

            $request->user(),

            $uuid

        );

        return back()->with(

            'success',

            'Assignment ditolak.'

        );

    }

    /**
     * Check In Assignment
     */
    public function checkIn(
        Request $request,
        string $uuid
    ) {

        $request->validate([

            'latitude' => ['required', 'numeric'],

            'longitude' => ['required', 'numeric'],

        ]);

        $result = $this->assignmentService->checkIn(

            $request->user(),

            $uuid,

            (float) $request->latitude,

            (float) $request->longitude,

            $this->attendanceService

        );

        if (!$result['success']) {

            $message = $result['message'];

            if (isset($result['distance'])) {

                $message .= sprintf(

                    ' (Distance: %sm, Allowed: %sm)',

                    round($result['distance']),

                    $result['radius']

                );

            }

            return back()->with('error', $message);

        }

        return back()->with(

            'success',

            'Check in assignment berhasil.'

        );

    }

    /**
     * Check Out Assignment
     */
    public function checkOut(
        Request $request,
        string $uuid
    ) {

        $request->validate([

            'latitude' => ['required', 'numeric'],

            'longitude' => ['required', 'numeric'],

        ]);

        $result = $this->assignmentService->checkOut(

            $request->user(),

            $uuid,

            (float) $request->latitude,

            (float) $request->longitude,

            $this->attendanceService

        );

        if (!$result['success']) {

            $message = $result['message'];

            if (isset($result['distance'])) {

                $message .= sprintf(

                    ' (Distance: %sm, Allowed: %sm)',

                    round($result['distance']),

                    $result['radius']

                );

            }

            return back()->with('error', $message);

        }

        return back()->with(

            'success',

            'Check out assignment berhasil.'

        );

    }

    /**
     * Complete Assignment
     */
    public function complete(
        Request $request,
        string $uuid
    ) {

        $request->validate([

            'completion_photo' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png',
                'max:5120', // 5MB
            ],

        ], [

            'completion_photo.required' => 'Foto bukti selesai wajib diupload.',

            'completion_photo.image' => 'File harus berupa gambar.',

            'completion_photo.max' => 'Ukuran foto maksimal 5MB.',

        ]);

        $this->assignmentService->complete(

            $request->user(),

            $uuid,

            $request->file('completion_photo')

        );

        return back()->with(

            'success',

            'Assignment berhasil diselesaikan.'

        );

    }
}