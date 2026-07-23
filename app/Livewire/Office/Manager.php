<?php

namespace App\Livewire\Office;

use App\Services\OfficeService;
use Livewire\Component;

class Manager extends Component
{
    public function render(OfficeService $officeService)
    {
        return view('livewire.office.manager', [
            'offices' => $officeService->summary(),
        ]);
    }
}