<?php

namespace App\Livewire\Platform;

use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyManager extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $status = '';

    #[Url(history: true)]
    public string $plan = '';

    public ?string $successMessage = null;

    public ?string $errorMessage = null;

    protected $paginationTheme = 'tailwind';

    public function mount(): void
    {
        Gate::authorize('viewAny', Company::class);
    }

    public function updated($property): void
    {
        if (in_array($property, ['search', 'status', 'plan'])) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'status', 'plan']);
        $this->resetPage();
    }

    public function toggleStatus(int $companyId, CompanyService $companyService): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        $this->dispatch('action-loading');

        try {

            $company = Company::findOrFail($companyId);

            Gate::authorize('update', $company);

            $company = $companyService->toggleStatus($company);

            $this->successMessage = $company->is_active
                ? 'Company berhasil diaktifkan.'
                : 'Company berhasil dinonaktifkan.';

            $this->dispatch('action-complete');

        } catch (\Throwable $e) {

            report($e);

            $this->errorMessage = 'Gagal mengubah status company.';
            $this->dispatch('action-loading-done');

        }
    }

    public function deleteCompany(int $companyId, CompanyService $companyService): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        $this->dispatch('action-loading');

        try {

            $company = Company::findOrFail($companyId);

            Gate::authorize('delete', $company);

            $companyService->delete($company);

            $this->successMessage = 'Company berhasil dihapus.';
            $this->dispatch('action-complete');

        } catch (\Throwable $e) {

            report($e);

            $this->errorMessage = 'Gagal menghapus company.';
            $this->dispatch('action-loading-done');

        }
    }

    public function render(CompanyService $companyService)
    {
        $companies = $companyService->getAll([
            'search' => $this->search,
            'status' => $this->status,
            'plan' => $this->plan,
            'per_page' => 10,
        ]);

        return view('livewire.platform.company-manager', [
            'companies' => $companies,
            'statistics' => $companyService->statistics(),
        ]);
    }
}