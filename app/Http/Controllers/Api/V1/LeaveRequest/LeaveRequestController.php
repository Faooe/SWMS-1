<?php

namespace App\Http\Controllers\Api\V1\LeaveRequest;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\LeaveRequestResource;
use App\Models\LeaveRequest;
use App\Services\LeaveRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LeaveRequestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Leave Request Controller (Company Admin / Super Admin)
    |--------------------------------------------------------------------------
    |
    | Melengkapi endpoint approve/reject pengajuan izin yang sebelumnya
    | cuma tersedia di web (app/Http/Controllers/Web/LeaveRequestController).
    | Route dilindungi middleware 'role:SUPER_ADMIN' -- lihat routes/api.php.
    |
    */

    public function __construct(
        protected LeaveRequestService $leaveRequestService
    ) {
    }

    /**
     * List All Leave Requests (Company)
     */
    public function index(Request $request): JsonResponse
    {
        $leaveRequests = $this->leaveRequestService->getAll(
            $request->all()
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
     * Approve Leave Request
     */
    public function approve(
        Request $request,
        LeaveRequest $leave
    ): JsonResponse {

        if ((int) $leave->company_id !== (int) $request->user()?->company_id) {
            return ResponseHelper::error('Pengajuan izin tidak ditemukan.', null, 404);
        }

        try {

            $leaveRequest = $this->leaveRequestService->approve(
                $leave,
                $request->user()
            );

        } catch (ValidationException $exception) {

            return ResponseHelper::error(
                collect($exception->errors())->flatten()->first()
                    ?? 'Pengajuan izin tidak dapat disetujui.',
                $exception->errors(),
                422
            );

        }

        return ResponseHelper::success(
            new LeaveRequestResource($leaveRequest),
            'Pengajuan izin berhasil disetujui.'
        );
    }

    /**
     * Reject Leave Request
     */
    public function reject(
        Request $request,
        LeaveRequest $leave
    ): JsonResponse {

        if ((int) $leave->company_id !== (int) $request->user()?->company_id) {
            return ResponseHelper::error('Pengajuan izin tidak ditemukan.', null, 404);
        }

        $request->validate([

            'rejection_reason' => ['nullable', 'string', 'max:1000'],

        ]);

        try {

            $leaveRequest = $this->leaveRequestService->reject(
                $leave,
                $request->user(),
                $request->rejection_reason
            );

        } catch (ValidationException $exception) {

            return ResponseHelper::error(
                collect($exception->errors())->flatten()->first()
                    ?? 'Pengajuan izin tidak dapat ditolak.',
                $exception->errors(),
                422
            );

        }

        return ResponseHelper::success(
            new LeaveRequestResource($leaveRequest),
            'Pengajuan izin ditolak.'
        );
    }
}
