<?php

namespace App\Livewire\Office;

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
    public string $province = '';

    #[Url(history: true)]
    public string $city = '';

    #[Url(history: true)]
    public string $status = '';

    #[Url(history: true)]
    public int $perPage = 10;

    protected $paginationTheme = 'tailwind';

    public function updated($property): void
    {
        if (in_array($property, ['search', 'province', 'city', 'status', 'perPage'])) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'province', 'city', 'status']);
        $this->resetPage();
    }

    public function render(OfficeService $officeService)
    {
        $offices = $officeService->getOffices([
            'search' => $this->search,
            'province' => $this->province,
            'city' => $this->city,
            'status' => $this->status,
            'per_page' => $this->perPage,
        ]);

        return view('livewire.office.manager', [
            'offices' => $offices,
            'provinces' => $officeService->provinces(),
            'cities' => $officeService->cities(),
        ]);
    }
}