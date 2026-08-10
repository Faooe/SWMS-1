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

        // smartCheckIn(): kalau employee sedang punya assignment aktif
        // (Assigned/Accepted/In Progress), otomatis divalidasi terhadap
        // lokasi assignment, bukan office -- sama seperti logic di web
        // (EmployeeAttendanceController). checkIn() biasa dipakai sebagai
        // fallback saat tidak ada assignment aktif.
        $attendance = $this->attendanceService->smartCheckIn(
            $user,
            $request->validated()
        );

        // Load relasi agar AttendanceResource tidak melakukan query tambahan
        $attendance->load([
            'employee',
            'office',
            'shift',
            'assignment',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check In berhasil.',
            'data' => new AttendanceResource($attendance),
        ], 201);
    }

    /**
     * Check-in context: office & assignment aktif employee, terlepas dari
     * status check-in hari ini. Dipakai layar Attendance untuk menampilkan
     * peta/radius office (dan kartu assignment kalau ada) sebelum karyawan
     * menekan Check In -- sebelumnya cuma tersedia lewat today(), yang
     * null selama belum check-in.
     */
    public function context(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user();

        if (!$user) {

            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);

        }

        $context = $this->attendanceService->checkInContext($user);

        return response()->json([
            'success' => true,
            'message' => 'Konteks absensi berhasil diambil.',
            'data' => [
                'office' => $context['office'] ? [
                    'id' => $context['office']->id,
                    'code' => $context['office']->code,
                    'name' => $context['office']->name,
                    'latitude' => $context['office']->latitude,
                    'longitude' => $context['office']->longitude,
                    'radius' => $context['office']->radius,
                ] : null,
                'assignment' => $context['assignment'] ? [
                    'id' => $context['assignment']->id,
                    'title' => $context['assignment']->title,
                    'latitude' => $context['assignment']->latitude,
                    'longitude' => $context['assignment']->longitude,
                    'radius' => $context['assignment']->radius,
                    'start_datetime' => $context['assignment']->start_datetime,
                    'end_datetime' => $context['assignment']->end_datetime,
                ] : null,
            ],
        ]);
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

        // smartCheckOut(): baca attendance_type dari record hari ini lalu
        // arahkan ke checkOut() (office) atau checkOutAssignment() -- selaras
        // dengan smartCheckIn() di atas.
        $attendance = $this->attendanceService->smartCheckOut(
            $user,
            $request->validated()
        );

        $attendance->load([
            'employee',
            'office',
            'shift',
            'assignment',
        ]);

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