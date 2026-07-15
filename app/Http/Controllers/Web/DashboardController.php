<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {
    }

    public function index(Request $request)
    {
        return view(
            'dashboard.index',
            $this->dashboardService->index($request->user())
        );
    }
}