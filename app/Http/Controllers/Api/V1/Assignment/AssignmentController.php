<?php

namespace App\Http\Controllers\Api\V1\Assignment;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Assignment\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;
use App\Services\AssignmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function __construct(
        protected AssignmentService $assignmentService
    ) {
    }

    /**
     * List Assignment (Company / Admin scope)
     */
    public function index(Request $request): JsonResponse
    {
        $assignments = $this->assignmentService->getAll(
            $request->all()
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
            'Data assignment berhasil diambil.'
        );
    }

    /**
     * Detail Assignment
     */
    public function show(int $id): JsonResponse
    {
        $assignment = $this->assignmentService->find($id);

        if (!$assignment) {

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
     * Statistics -- versi API dari perhitungan inline di
     * Web\AssignmentController::index() ($statistics), supaya mobile
     * tidak perlu menghitung total/draft/active/completed sendiri dari
     * data yang dipaginate (yang cuma berisi 1 halaman, bukan semua baris).
     */
    public function statistics(): JsonResponse
    {
        return ResponseHelper::success(
            [
                'total' => Assignment::query()->forCurrentCompany()->count(),
                'draft' => Assignment::query()->forCurrentCompany()->draft()->count(),
                'active' => Assignment::query()->forCurrentCompany()->active()->count(),
                'completed' => Assignment::query()->forCurrentCompany()->completed()->count(),
            ],
            'Statistik assignment berhasil diambil.'
        );
    }

    /**
     * Store Assignment
     */
    public function store(
        StoreAssignmentRequest $request
    ): JsonResponse {

        $user = $request->user();

        $assignment = $this->assignmentService->create(
            $request->validated(),
            $user->id
        );

        return ResponseHelper::success(
            new AssignmentResource($assignment),
            'Assignment berhasil dibuat.',
            201
        );
    }

    /**
     * Update Assignment
     *
     * Company scope dijaga oleh AssignmentService::update() lewat
     * authorizeCompany(); route parameter pakai implicit binding
     * ({assignment}) supaya 404 otomatis kalau ID tidak ditemukan.
     */
    public function update(
        UpdateAssignmentRequest $request,
        Assignment $assignment
    ): JsonResponse {

        $assignment = $this->assignmentService->update(
            $assignment,
            $request->validated()
        );

        return ResponseHelper::success(
            new AssignmentResource($assignment),
            'Assignment berhasil diperbarui.'
        );
    }

    /**
     * Delete Assignment
     */
    public function destroy(Assignment $assignment): JsonResponse
    {
        $this->assignmentService->delete($assignment);

        return ResponseHelper::success(
            null,
            'Assignment berhasil dihapus.'
        );
    }
}
