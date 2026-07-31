<?php

namespace App\Livewire\Assignment;

use App\Models\Assignment;
use App\Models\Employee;
use App\Services\AssignmentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EmployeeManager extends Component
{
    public Assignment $assignment;

    /*
    |--------------------------------------------------------------------------
    | Picker State
    |--------------------------------------------------------------------------
    */

    public bool $showPicker = false;

    public string $search = '';

    public string $statusFilter = '';

    public string $busyFilter = '';

    public ?string $successMessage = null;

    public ?string $errorMessage = null;

    public function mount(Assignment $assignment): void
    {
        abort_unless(
            $assignment->company_id === Auth::user()->company_id,
            403
        );

        $this->assignment = $assignment;
    }

    /*
    |--------------------------------------------------------------------------
    | Open / Close Picker
    |--------------------------------------------------------------------------
    */

    public function openPicker(): void
    {
        $this->reset(['search', 'statusFilter', 'busyFilter']);

        $this->showPicker = true;
    }

    public function closePicker(): void
    {
        $this->showPicker = false;
    }

    /*
    |--------------------------------------------------------------------------
    | Add / Remove Employee (no page reload)
    |--------------------------------------------------------------------------
    */

    public function addEmployee(int $employeeId, AssignmentService $assignmentService): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        $this->dispatch('action-loading');

        try {

            $assignmentService->addEmployee($this->assignment, $employeeId);

            $this->assignment->refresh();

            $this->successMessage = 'Employee berhasil ditambahkan ke assignment.';
            $this->dispatch('action-complete');

        } catch (\Throwable $e) {

            report($e);

            $this->errorMessage = 'Gagal menambahkan employee. Silakan coba lagi.';
            $this->dispatch('action-loading-done');

        }
    }

    public function removeEmployee(int $employeeId, AssignmentService $assignmentService): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        $this->dispatch('action-loading');

        try {

            $assignmentService->removeEmployee($this->assignment, $employeeId);

            $this->assignment->refresh();

            $this->successMessage = 'Employee berhasil dihapus dari assignment.';
            $this->dispatch('action-complete');

        } catch (\Throwable $e) {

            report($e);

            $this->errorMessage = 'Gagal menghapus employee. Silakan coba lagi.';
            $this->dispatch('action-loading-done');

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        $companyId = Auth::user()->company_id;

        $assignedIds = $this->assignment
            ->employees()
            ->pluck('employees.id')
            ->toArray();

        $availableEmployees = collect();

        if ($this->showPicker) {

            $availableEmployees = Employee::query()
                ->where('company_id', $companyId)
                ->where('is_active', true)
                ->with(['currentEmployment.position', 'currentEmployment.office'])
                ->when($this->search, fn ($q) => $q->where(
                    'full_name',
                    'ILIKE',
                    "%{$this->search}%"
                ))
                ->when($this->statusFilter === 'active', fn ($q) => $q->where('is_active', true))
                ->when($this->statusFilter === 'inactive', fn ($q) => $q->where('is_active', false))
                ->orderBy('full_name')
                ->get()
                ->filter(function (Employee $employee) use ($assignedIds) {

                    if ($this->busyFilter === 'free') {
                        return !$employee->hasCurrentAssignment() && !in_array($employee->id, $assignedIds);
                    }

                    if ($this->busyFilter === 'busy') {
                        return $employee->hasCurrentAssignment();
                    }

                    return !in_array($employee->id, $assignedIds);

                });
        }

        return view('livewire.assignment.employee-manager', [
            'employees' => $this->assignment->employees()->with([
                'currentEmployment.position',
                'currentEmployment.office',
            ])->get(),
            'availableEmployees' => $availableEmployees,
        ]);
    }
}
