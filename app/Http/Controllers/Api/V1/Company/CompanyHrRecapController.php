<?php

namespace App\Http\Controllers\Api\V1\Company;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\CompanyHrRecapController as WebCompanyHrRecapController;
use App\Services\CompanyHrRecapService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyHrRecapController extends Controller
{
    public function __construct(private readonly CompanyHrRecapService $recapService) {}

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
            'search' => ['nullable', 'string', 'max:100'],
            'office_id' => ['nullable', 'integer'],
            'department_id' => ['nullable', 'integer'],
            'position_id' => ['nullable', 'integer'],
            'team_id' => ['nullable', 'integer'],
            'active' => ['nullable', 'in:0,1'],
            'sort' => ['nullable', 'in:name,attendance_low,absent_high,completion_low,not_worked_high'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $company = $request->user()->company;
        abort_unless($company, 404);
        $recap = $this->recapService->recap($company, $request);
        $rows = $recap['rows'];

        return ResponseHelper::success([
            'range' => $recap['range'],
            'summary' => $recap['summary'],
            'items' => $rows->items(),
            'pagination' => [
                'current_page' => $rows->currentPage(),
                'last_page' => $rows->lastPage(),
                'per_page' => $rows->perPage(),
                'total' => $rows->total(),
            ],
            'export' => [
                'available' => $company->isPremium(),
                'minimum_plan' => 'Premium Go',
                'current_plan' => $company->subscription_plan,
            ],
        ], 'Rekap HR perusahaan berhasil diambil.');
    }

    public function exportPdf(Request $request, WebCompanyHrRecapController $controller)
    {
        return $controller->exportPdf($request);
    }

    public function exportExcel(Request $request, WebCompanyHrRecapController $controller)
    {
        return $controller->exportExcel($request);
    }
}
