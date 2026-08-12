<?php

namespace App\Http\Controllers\Api\V1\Employee;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequest\StoreLeaveRequestRequest;
use App\Http\Resources\LeaveRequestResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Services\LeaveRequestService;
use App\Services\LeaveQuotaService;

class LeaveRequestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Leave Request Controller (Employee)
    |--------------------------------------------------------------------------
    |
    | Sebelumnya API sama sekali tidak punya endpoint pengajuan izin,
    | padahal fiturnya sudah lengkap di web (lihat
    | app/Http/Controllers/Web/Employee/LeaveRequestController.php dan
    | App\Services\LeaveRequestService). Controller ini memakai ulang
    | service yang sama.
    |
    */

    public function __construct(
        protected LeaveRequestService $leaveRequestService,
        protected LeaveQuotaService $leaveQuotaService
    ) {
    }

    /**
     * List My Leave Requests
     */
    public function index(Request $request): JsonResponse
    {
        $employee = $request->user()?->employee;

        if (!$employee) {

            return ResponseHelper::error(
                'Data karyawan tidak ditemukan untuk user ini.',
                null,
                404
            );

        }

        $leaveRequests = $this->leaveRequestService->getForEmployee(
            $employee,
            $request->only([
                'status',
                'per_page',
            ])
        );

        return ResponseHelper::success(
            [
                'items' => LeaveRequestResource::collection(
                    $leaveRequests->items()
                ),
                'pagination' => [
                    'current_page' => $leaveRequests->currentPage(),
                    'last_page' => $leaveRequests->lastPage(),
                    'per_page' => $leaveRequests->perPage(),
                    'total' => $leaveRequests->total(),
                ]
            ],
            'Data pengajuan izin berhasil diambil.'
        );
    }

    /**
     * Submit Leave Request
     */
    public function store(StoreLeaveRequestRequest $request): JsonResponse
    {
        $employee = Auth::user()?->employee;

        if (!$employee) {

            return ResponseHelper::error(
                'Data karyawan tidak ditemukan untuk user ini.',
                null,
                404
            );

        }

        try {

            $leaveRequest = $this->leaveRequestService->submit(
                $employee,
                $request->validated()
            );

        } catch (ValidationException $exception) {

            return ResponseHelper::error(
                collect($exception->errors())->flatten()->first()
                    ?? 'Pengajuan izin gagal.',
                $exception->errors(),
                422
            );

        }

        return ResponseHelper::success(
            new LeaveRequestResource($leaveRequest),
            'Pengajuan izin berhasil dikirim, menunggu persetujuan admin.',
            201
        );
    }

    /**
     * Kuota Cuti Tahun Berjalan (punya sendiri)
     *
     * Dipakai form pengajuan izin (web & mobile) untuk menampilkan
     * "Sisa Cuti: X/Y hari" -- support ?year=YYYY untuk lihat tahun
     * lain (mis. rekap tahun lalu), default tahun berjalan.
     */
    public function quota(Request $request): JsonResponse
    {
        $employee = $request->user()?->employee;

        if (!$employee) {

            return ResponseHelper::error(
                'Data karyawan tidak ditemukan untuk user ini.',
                null,
                404
            );

        }

        $year = (int) ($request->query('year') ?: now()->year);

        return ResponseHelper::success(
            $this->leaveQuotaService->summary($employee, $year),
            'Kuota cuti berhasil diambil.'
        );
    }
}
