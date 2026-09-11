<?php

namespace App\Livewire\Office;

use App\Models\Office;
use App\Services\OfficeService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Manager extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $isActive = '';

    protected $paginationTheme = 'tailwind';

    public function updated($property): void
    {
        if (in_array($property, ['search', 'isActive'])) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'isActive']);
        $this->resetPage();
    }

    public function render(OfficeService $officeService)
    {
        return view('livewire.office.manager', [
            'offices' => $officeService->getOffices([
                'search' => $this->search,
                'status' => $this->isActive,
                'per_page' => 10,
            ]),
            'statistics' => [
                'total' => Office::forCurrentCompany()->count(),
                'active' => Office::forCurrentCompany()->where('is_active', true)->count(),
                'inactive' => Office::forCurrentCompany()->where('is_active', false)->count(),
            ],
        ]);
    }
}
