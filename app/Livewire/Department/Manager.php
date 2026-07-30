<?php

namespace App\Livewire\Department;

use App\Models\Department;
use Illuminate\Support\Facades\Auth;
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

    public ?string $successMessage = null;

    public ?string $errorMessage = null;

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

    private function authorizeCompany(Department $department): void
    {
        abort_unless(
            $department->company_id === Auth::user()->company_id,
            403,
            'Anda tidak memiliki akses ke department ini.'
        );
    }

    public function toggleStatus(int $departmentId): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        $this->dispatch('action-loading');

        try {

            $department = Department::findOrFail($departmentId);
            $this->authorizeCompany($department);

            $department->update(['is_active' => !$department->is_active]);

            $this->successMessage = $department->is_active
                ? 'Department berhasil diaktifkan.'
                : 'Department berhasil dinonaktifkan.';

            $this->dispatch('action-complete');

        } catch (\Throwable $e) {

            report($e);

            $this->errorMessage = 'Gagal mengubah status department.';
            $this->dispatch('action-loading-done');

        }
    }

    public function deleteDepartment(int $departmentId): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        $this->dispatch('action-loading');

        try {

            $department = Department::findOrFail($departmentId);
            $this->authorizeCompany($department);

            if ($department->teams()->exists()) {

                $this->errorMessage = 'Department tidak bisa dihapus karena masih memiliki Team.';
                $this->dispatch('action-loading-done');

                return;
            }

            $department->delete();

            $this->successMessage = 'Department berhasil dihapus.';
            $this->dispatch('action-complete');

        } catch (\Throwable $e) {

            report($e);

            $this->errorMessage = 'Gagal menghapus department.';
            $this->dispatch('action-loading-done');

        }
    }

    public function render()
    {
        $departments = Department::query()
            ->forCurrentCompany()
            ->withCount('teams')
            ->when($this->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($this->isActive !== '', fn ($query) => $query->where('is_active', $this->isActive === '1'))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.department.manager', [
            'departments' => $departments,
            'statistics' => [
                'total' => Department::forCurrentCompany()->count(),
                'active' => Department::forCurrentCompany()->where('is_active', true)->count(),
                'inactive' => Department::forCurrentCompany()->where('is_active', false)->count(),
            ],
        ]);
    }
}
