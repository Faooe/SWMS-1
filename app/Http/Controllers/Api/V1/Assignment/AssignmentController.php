<?php

namespace App\Http\Controllers\Api\V1\Assignment;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Assignment\StoreAssignmentRequest;
use App\Http\Resources\AssignmentResource;
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
     * List Assignment
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
}