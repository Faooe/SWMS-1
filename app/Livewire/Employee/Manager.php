<?php

namespace App\Livewire\Employee;

use App\Models\Department;
use App\Models\Employee;
use App\Services\EmployeeService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Manager extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $department = '';

    #[Url(history: true)]
    public string $isActive = '';

    #[Url(history: true)]
    public string $sort = 'full_name';

    #[Url(history: true)]
    public string $direction = 'asc';

    public int $perPage = 10;

    public ?string $successMessage = null;

    public ?string $errorMessage = null;

    protected $paginationTheme = 'tailwind';

    /*
    |--------------------------------------------------------------------------
    | Reset pagination whenever a filter changes
    |--------------------------------------------------------------------------
    */

    public function updated($property): void
    {
        if (in_array($property, ['search', 'department', 'isActive'])) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'department', 'isActive']);
        $this->resetPage();
    }

    public function sortBy(string $column): void
    {
        if ($this->sort === $column) {
            $this->direction = $this->direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort = $column;
            $this->direction = 'asc';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Actions (reactive, no page reload)
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(int $employeeId, EmployeeService $employeeService): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        $this->dispatch('action-loading');

        try {

            $employee = Employee::query()
                ->where('company_id', auth()->user()->company_id)
                ->findOrFail($employeeId);

            $employee = $employeeService->toggleStatus($employee);

            $this->successMessage = $employee->is_active
                ? 'Employee berhasil diaktifkan.'
                : 'Employee berhasil dinonaktifkan.';

            $this->dispatch('action-complete');

        } catch (\Throwable $e) {

            report($e);

            $this->errorMessage = 'Gagal mengubah status employee.';
            $this->dispatch('action-loading-done');

        }
    }

    public function deleteEmployee(int $employeeId, EmployeeService $employeeService): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        $this->dispatch('action-loading');

        try {

            $employee = Employee::query()
                ->where('company_id', auth()->user()->company_id)
                ->findOrFail($employeeId);

            $employeeService->delete($employee);

            $this->successMessage = 'Employee berhasil dihapus.';
            $this->dispatch('action-complete');

        } catch (\Throwable $e) {

            report($e);

            $this->errorMessage = 'Gagal menghapus employee. Pastikan employee tidak sedang punya data terkait yang aktif.';
            $this->dispatch('action-loading-done');

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render(EmployeeService $employeeService)
    {
        $companyId = auth()->user()->company_id;

        $employees = $employeeService->getAll([

            'search' => $this->search,

            'department' => $this->department,

            'is_active' => $this->isActive,

            'sort' => $this->sort,

            'direction' => $this->direction,

            'per_page' => $this->perPage,

        ]);

        $statistics = [

            'total' => Employee::query()->forCurrentCompany()->count(),

            'active' => Employee::query()->forCurrentCompany()->where('is_active', true)->count(),

            'inactive' => Employee::query()->forCurrentCompany()->where('is_active', false)->count(),

            'new_this_month' => Employee::query()
                ->forCurrentCompany()
                ->whereMonth('created_at', now()->month)
                ->count(),

        ];

        $departments = Department::query()
            ->forCurrentCompany()
            ->orderBy('name')
            ->get();

        return view('livewire.employee.manager', [
            'employees' => $employees,
            'statistics' => $statistics,
            'departments' => $departments,
        ]);
    }
}
