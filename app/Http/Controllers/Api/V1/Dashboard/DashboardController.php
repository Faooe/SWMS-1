<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {
    }

    /**
     * Dashboard.
     */
    public function index(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user();

        if (!$user) {

            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);

        }

       return ResponseHelper::success(

        $this->dashboardService->index($user),

        'Dashboard berhasil diambil.');
    }
}