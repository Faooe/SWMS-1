<?php

namespace App\Http\Controllers\Api\V1\Employee;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Assignment\AssignmentLocationRequest;
use App\Http\Requests\Assignment\CompleteAssignmentRequest;
use App\Http\Resources\AssignmentResource;
use App\Models\User;
use App\Services\Attendance\AttendanceService;
use App\Services\EmployeeAssignmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AssignmentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | My Assignment Controller (Employee)
    |--------------------------------------------------------------------------
    |
    | Sebelumnya endpoint API assignment (/v1/assignments) hanya berupa
    | listing perusahaan (admin scope) tanpa ada satupun endpoint untuk
    | employee menerima/menolak/check-in/check-out/menyelesaikan
    | assignment miliknya sendiri -- padahal ini fitur inti aplikasi
    | mobile untuk role EMPLOYEE. Controller ini melengkapi itu, memakai
    | ulang App\Services\EmployeeAssignmentService yang sudah dipakai
    | oleh web (app/Http/Controllers/Web/Employee/AssignmentController).
    |
    */

    public function __construct(
        protected EmployeeAssignmentService $assignmentService,
        protected AttendanceService $attendanceService
    ) {
    }

    /**
     * List My Assignments
     */
    public function index(Request $request): JsonResponse
    {
        $assignments = $this->assignmentService->getAssignments(

            $request->user(),

            $request->only([
                'search',
                'status',
                'priority',
                'date',
                'per_page',
            ])

        );

        return ResponseHelper::success(
            [
                'items' => AssignmentResource::collection(
                    $assignments->items()
                ),
                'pagination' => [
                    'current_page' => $assignments->currentPage(),
                    'last_page' => $assignments->lastPage(),
                    'per_page' => $assignments->perPage(),
                    'total' => $assignments->total(),
                ]
            ],
            'Data assignment saya berhasil diambil.'
        );
    }

    /**
     * My Assignment Detail
     */
    public function show(Request $request, string $uuid): JsonResponse
    {
        try {

            $assignment = $this->assignmentService->find(
                $request->user(),
                $uuid
            );

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {

            return ResponseHelper::error(
                'Assignment tidak ditemukan.',
                null,
                404
            );

        }

        return ResponseHelper::success(
            new AssignmentResource($assignment),
            'Detail assignment berhasil diambil.'
        );
    }

    /**
     * Today's Assignment
     */
    public function today(Request $request): JsonResponse
    {
        $assignments = $this->assignmentService->today(
            $request->user()
        );

        return ResponseHelper::success(
            AssignmentResource::collection($assignments),
            'Assignment hari ini berhasil diambil.'
        );
    }

    /**
     * Assignment Statistics
     */
    public function statistics(Request $request): JsonResponse
    {
        return ResponseHelper::success(
            $this->assignmentService->statistics(
                $request->user()
            ),
            'Statistik assignment berhasil diambil.'
        );
    }

    /**
     * Accept Assignment
     */
    public function accept(Request $request, string $uuid): JsonResponse
    {
        try {

            $assignment = $this->assignmentService->accept(
                $request->user(),
                $uuid
            );

        } catch (ValidationException $exception) {

            return ResponseHelper::error(
                collect($exception->errors())->flatten()->first()
                    ?? 'Assignment tidak dapat diterima.',
                $exception->errors(),
                422
            );

        }

        return ResponseHelper::success(
            new AssignmentResource($assignment),
            'Assignment berhasil diterima.'
        );
    }

    /**
     * Reject Assignment
     */
    public function reject(Request $request, string $uuid): JsonResponse
    {
        try {

            $assignment = $this->assignmentService->reject(
                $request->user(),
                $uuid
            );

        } catch (ValidationException $exception) {

            return ResponseHelper::error(
                collect($exception->errors())->flatten()->first()
                    ?? 'Assignment tidak dapat ditolak.',
                $exception->errors(),
                422
            );

        }

        return ResponseHelper::success(
            new AssignmentResource($assignment),
            'Assignment ditolak.'
        );
    }

    /**
     * Check In Assignment
     */
    public function checkIn(
        AssignmentLocationRequest $request,
        string $uuid
    ): JsonResponse {

        $result = $this->assignmentService->checkIn(

            $request->user(),

            $uuid,

            (float) $request->latitude,

            (float) $request->longitude,

            $this->attendanceService

        );

        if (!$result['success']) {

            return ResponseHelper::error(
                $result['message'],
                [
                    'distance' => $result['distance'] ?? null,
                    'radius' => $result['radius'] ?? null,
                ],
                422
            );

        }

        return ResponseHelper::success(
            $result,
            'Check in assignment berhasil.'
        );
    }

    /**
     * Check Out Assignment
     */
    public function checkOut(
        AssignmentLocationRequest $request,
        string $uuid
    ): JsonResponse {

        $result = $this->assignmentService->checkOut(

            $request->user(),

            $uuid,

            (float) $request->latitude,

            (float) $request->longitude,

            $this->attendanceService

        );

        if (!$result['success']) {

            return ResponseHelper::error(
                $result['message'],
                [
                    'distance' => $result['distance'] ?? null,
                    'radius' => $result['radius'] ?? null,
                ],
                422
            );

        }

        return ResponseHelper::success(
            $result,
            'Check out assignment berhasil.'
        );
    }

    /**
     * Complete Assignment
     */
    public function complete(
        CompleteAssignmentRequest $request,
        string $uuid
    ): JsonResponse {

        try {

            $assignment = $this->assignmentService->complete(

                $request->user(),

                $uuid,

                $request->file('completion_photo')

            );

        } catch (ValidationException $exception) {

            return ResponseHelper::error(
                collect($exception->errors())->flatten()->first()
                    ?? 'Assignment belum bisa diselesaikan.',
                $exception->errors(),
                422
            );

        }

        return ResponseHelper::success(
            new AssignmentResource($assignment),
            'Assignment berhasil diselesaikan.'
        );
    }
}
