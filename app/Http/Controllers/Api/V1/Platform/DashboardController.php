<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Platform\CompanyResource;
use App\Services\Platform\DashboardService;
use Illuminate\Http\JsonResponse;

/**
 * Versi API dari App\Http\Controllers\Platform\DashboardController (web).
 * Dipakai oleh PlatformHomeScreen di Flutter -- lihat catatan di
 * lib/features/platform/data/platform_mock_data.dart (GET /platform/dashboard).
 */
class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {
    }

    /**
     * Ringkasan Dashboard Platform Admin.
     */
    public function index(): JsonResponse
    {
        $data = $this->dashboardService->index();

        return ResponseHelper::success(

            [

                // -> PlatformDashboardSummary.totalCompany/activeCompany/
                // inactiveCompany/premiumCompany
                'statistics' => $data['statistics'],

                // -> PlatformDashboardSummary.latestCompanies
                'latest_companies' => CompanyResource::collection(
                    $data['latestCompanies']
                ),

            ],

            'Data dashboard platform berhasil diambil.'

        );
    }
}
