<?php

namespace App\Http\Controllers\Api\V1\Master;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\MasterResource;
use App\Services\MasterService;
use Illuminate\Http\JsonResponse;

class MasterController extends Controller
{
    public function __construct(
        protected MasterService $masterService
    ) {
    }

    /**
     * Department List
     */
    public function departments(): JsonResponse
    {
        return ResponseHelper::success(
            MasterResource::collection(
                $this->masterService->departments()
            ),
            'Data department berhasil diambil.'
        );
    }

    /**
     * Position List
     */
    public function positions(): JsonResponse
    {
        return ResponseHelper::success(
            MasterResource::collection(
                $this->masterService->positions()
            ),
            'Data position berhasil diambil.'
        );
    }

    /**
     * Team List
     */
    public function teams(): JsonResponse
    {
        return ResponseHelper::success(
            MasterResource::collection(
                $this->masterService->teams()
            ),
            'Data team berhasil diambil.'
        );
    }

    /**
     * Office List
     */
    public function offices(): JsonResponse
    {
        return ResponseHelper::success(
            MasterResource::collection(
                $this->masterService->offices()
            ),
            'Data office berhasil diambil.'
        );
    }

    /**
     * Shift List
     */
    public function shifts(): JsonResponse
    {
        return ResponseHelper::success(
            MasterResource::collection(
                $this->masterService->shifts()
            ),
            'Data shift berhasil diambil.'
        );
    }
}