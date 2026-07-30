<?php

namespace App\Livewire;

use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(DashboardService $dashboardService)
    {
        return view('livewire.dashboard', $dashboardService->index(Auth::user()));
    }
}
