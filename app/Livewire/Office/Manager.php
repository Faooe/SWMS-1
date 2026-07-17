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
    public string $province = '';

    #[Url(history: true)]
    public string $city = '';

    #[Url(history: true)]
    public string $status = '';

    #[Url(history: true)]
    public int $perPage = 10;

    public ?string $successMessage = null;

    public ?string $errorMessage = null;

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

    public function deleteOffice(int $officeId, OfficeService $officeService): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        $this->dispatch('action-loading');

        try {

            $office = Office::query()
                ->where('company_id', auth()->user()->company_id)
                ->findOrFail($officeId);

            $officeService->destroy($office);

            $this->successMessage = 'Office berhasil dihapus.';
            $this->dispatch('action-complete');

        } catch (\Throwable $e) {

            report($e);

            $this->errorMessage = 'Gagal menghapus office. Pastikan office tidak sedang dipakai employee/assignment aktif.';
            $this->dispatch('action-loading-done');

        }
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
            'statistics' => $officeService->statistics(),
            'provinces' => $officeService->provinces(),
            'cities' => $officeService->cities(),
        ]);
    }
}
