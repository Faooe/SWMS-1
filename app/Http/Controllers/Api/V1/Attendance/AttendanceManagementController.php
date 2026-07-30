<?php

namespace App\Http\Controllers\Api\V1\Attendance;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Services\AttendanceManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceManagementController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Attendance Management Controller (Company Admin / Super Admin)
    |--------------------------------------------------------------------------
    |
    | Melengkapi endpoint untuk admin melihat absensi SELURUH karyawan di
    | company-nya (sebelumnya endpoint /attendance/* di API hanya untuk
    | absensi milik diri sendiri / employee). Memakai ulang
    | App\Services\AttendanceManagementService yang sama dengan web
    | (app/Http/Controllers/Web/AttendanceController), tanpa fitur
    | export PDF/Excel karena tidak relevan untuk aplikasi mobile.
    |
    */

    public function __construct(
        protected AttendanceManagementService $attendanceService
    ) {
    }

    /**
     * Attendance List (Company)
     */
    public function index(Request $request): JsonResponse
    {
        $attendances = $this->attendanceService->getAttendances(
            $request->only([
                'search',
                'office',
                'status',
                'date',
                'per_page',
            ])
        );

        return ResponseHelper::success(
            [
                'items' => AttendanceResource::collection(
                    $attendances->items()
                ),
                'pagination' => [
                    'current_page' => $attendances->currentPage(),
                    'last_page' => $attendances->lastPage(),
                    'per_page' => $attendances->perPage(),
                    'total' => $attendances->total(),
                ]
            ],
            'Data absensi karyawan berhasil diambil.'
        );
    }

    /**
     * Attendance Detail
     */
    public function show(int $id): JsonResponse
    {
        $attendance = $this->attendanceService->find($id);

        return ResponseHelper::success(
            new AttendanceResource($attendance),
            'Detail absensi berhasil diambil.'
        );
    }

    /**
     * Attendance Statistics (Company, per bulan)
     */
    public function statistics(Request $request): JsonResponse
    {
        return ResponseHelper::success(
            $this->attendanceService->statistics(
                $request->integer('year') ?: null,
                $request->integer('month') ?: null
            ),
            'Statistik absensi berhasil diambil.'
        );
    }
}
