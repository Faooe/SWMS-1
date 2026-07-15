<?php

namespace App\Services\Platform;

use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Support\Collection;

class DashboardService
{
    public function __construct(
        protected CompanyService $companyService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard Data
    |--------------------------------------------------------------------------
    */

    public function index(): array
    {
        return [

            'statistics' => $this->companyService->statistics(),

            'latestCompanies' => $this->getLatestCompanies(),

        ];

    }

    /*
    |--------------------------------------------------------------------------
    | Latest Companies
    |--------------------------------------------------------------------------
    */

    private function getLatestCompanies(): Collection
    {

        return Company::query()

            ->latest()

            ->take(5)

            ->get();

    }
}