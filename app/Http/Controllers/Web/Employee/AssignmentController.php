<?php

namespace App\Http\Controllers\Web\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assignment\CompleteAssignmentRequest;
use App\Http\Resources\AssignmentResource;
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
        $assignments = $this->assignmentService->getAssignments(
            $request->user(),
            $request->only([
                'search',
                'status',
                'priority',
                'per_page',
            ])
        );

        // Keep the web cards on the exact same employee-facing state source used
        // by the API/mobile client. This is especially important for Daily
        // Attendance progress and review-state labels.
        $assignments->setCollection(
            $assignments->getCollection()->map(function ($assignment) use ($request) {
                $assignment->setAttribute(
                    'employee_card_state',
                    (new AssignmentResource($assignment))->toArray($request)
                );

                return $assignment;
            })
        );

        return view('employee.assignments.index', [
            'assignments' => $assignments,
            'statistics' => $this->assignmentService->statistics($request->user()),
        ]);
    }

    /**
     * Assignment Detail
     */
    public function show(
        Request $request,
        string $uuid
    ) {

        $assignment = $this->assignmentService
            ->find(
                $request->user(),
                $uuid
            );

        /*
        |--------------------------------------------------------------------------
        | Gunakan state yang sama dengan API/mobile
        |--------------------------------------------------------------------------
        |
        | Sebelumnya web Role 3 punya logika tombol/status sendiri sehingga
        | tertinggal dari Phase 3 (Daily Attendance, review status, grace period,
        | dan attendance per-assignment). AssignmentResource sudah menjadi sumber
        | aturan yang dipakai mobile, jadi detail web juga memakai state yang sama
        | agar tidak terjadi perbedaan perilaku antar platform.
        */
        $assignmentState = (new AssignmentResource($assignment))->toArray($request);

        return view(
            'employee.assignments.show',
            [
                'assignment' => $assignment,
                'assignmentState' => $assignmentState,
                'myActions' => $assignmentState['my_actions'] ?? [],
                'dailyAttendance' => $assignmentState['my_daily_attendance'] ?? [],
                'dailyAttendanceSummary' => $assignmentState['my_daily_attendance_summary'] ?? null,
                'visibleLogs' => collect($assignmentState['logs'] ?? []),
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

        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'reason.required' => 'Alasan penolakan wajib diisi.',
            'reason.min' => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $this->assignmentService->reject(

            $request->user(),

            $uuid,

            $validated['reason']

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

            'work_description' => ['nullable', 'string', 'max:3000'],

            'work_photos' => ['nullable', 'array', 'max:3'],

            'work_photos.*' => ['file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],

        ]);

        $result = $this->assignmentService->checkOut(

            $request->user(),

            $uuid,

            (float) $request->latitude,

            (float) $request->longitude,

            $this->attendanceService,

            $request->input('work_description'),

            $request->file('work_photos', [])

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
     * Request missed Check Out correction (Daily Attendance only).
     * Check In yang terlupa sengaja tidak memiliki jalur koreksi.
     */
    public function requestCheckoutCorrection(
        Request $request,
        string $uuid,
        \App\Services\AttendanceCheckoutCorrectionService $correctionService
    ) {
        $data = $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
            'requested_check_out_time' => ['required', 'date_format:H:i'],
            'reason' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $assignment = $this->assignmentService->find($request->user(), $uuid);
        $correctionService->request($request->user(), $assignment, $data['date'], $data['requested_check_out_time'], $data['reason']);

        return back()->with('success', 'Pengajuan koreksi Check Out dikirim ke Company.');
    }

    public function dailyReportPdf(
        Request $request,
        string $uuid,
        \App\Services\DailyAssignmentReportService $reportService
    ) {
        return $reportService->downloadForEmployee($request->user(), $uuid);
    }

    /**
     * Complete Assignment
     */
    public function complete(
        CompleteAssignmentRequest $request,
        string $uuid
    ) {

        try {

            $this->assignmentService->complete(

                $request->user(),

                $uuid,

                $request->file('completion_photo'),

                $request->file('completion_photo_2'),

                $request->validated('completion_notes')

            );

        } catch (\Illuminate\Validation\ValidationException $exception) {

            return back()->withErrors($exception->errors());

        }

        return back()->with(

            'success',

            'Assignment berhasil diselesaikan.'

        );

    }
}