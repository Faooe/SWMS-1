<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Services\Platform\DashboardService;

class DashboardController extends Controller
{
    public function __construct(

        protected DashboardService $dashboardService

    ) {
    }

    public function index()
    {
        return view(

            'platform.dashboard.index',

            $this->dashboardService->index()

        );
    }
}