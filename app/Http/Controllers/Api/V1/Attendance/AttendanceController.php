<?php

namespace App\Http\Controllers\Api\V1\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\CheckInRequest;
use App\Http\Resources\AttendanceResource;
use App\Http\Requests\Attendance\CheckOutRequest;
use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(
        protected AttendanceService $attendanceService
    ) {
    }

    /**
     * Employee Check In.
     */
    public function checkIn(
        CheckInRequest $request
    ): JsonResponse {

        /** @var User|null $user */
        $user = $request->user();

        if (!$user) {

            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);

        }

        $attendance = $this->attendanceService->checkIn(
            $user,
            $request->validated()
        );

        // Load relasi agar AttendanceResource tidak melakukan query tambahan
        $attendance->load([
            'employee',
            'office',
            'shift',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check In berhasil.',
            'data' => new AttendanceResource($attendance),
        ], 201);
    }

    /**
     * Get today's attendance.
     */
    public function today(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user();

        if (!$user) {

            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);

        }

        $attendance = $this->attendanceService->today($user);

        return response()->json([
            'success' => true,
            'message' => 'Data absensi hari ini berhasil diambil.',
            'data' => $attendance
                ? new AttendanceResource($attendance)
                : null,
        ]);
    }
        /**
     * Employee Check Out.
     */
    public function checkOut(
        CheckOutRequest $request
    ): JsonResponse {

        /** @var User|null $user */
        $user = $request->user();

        if (!$user) {

            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);

        }

        $attendance = $this->attendanceService->checkOut(
            $user,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Check Out berhasil.',
            'data' => new AttendanceResource($attendance),
        ]);
    }
        /**
     * Attendance History.
     */
    public function history(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user();

        if (!$user) {

            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);

        }

        $history = $this->attendanceService->history($user);

        return response()->json([
            'success' => true,
            'message' => 'Riwayat absensi berhasil diambil.',
            'data' => AttendanceResource::collection(
                $history->items()
            ),
            'pagination' => [
                'current_page' => $history->currentPage(),
                'last_page' => $history->lastPage(),
                'per_page' => $history->perPage(),
                'total' => $history->total(),
            ]
        ]);
    }
}