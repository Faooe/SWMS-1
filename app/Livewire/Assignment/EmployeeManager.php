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
    | Review Hasil Kerja (Approve / Reject)
    |--------------------------------------------------------------------------
    */

    public ?int $reviewingEmployeeId = null;

    public string $rejectNotes = '';

    public ?int $rejectMinutes = null;

    public function openReject(int $employeeId): void
    {
        $this->reviewingEmployeeId = $employeeId;
        $this->rejectNotes = '';
        $this->rejectMinutes = null;
    }

    public function closeReject(): void
    {
        $this->reviewingEmployeeId = null;
    }

    public function approveCompletion(int $employeeId, AssignmentService $assignmentService): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        try {

            $assignmentService->approveCompletion(
                $this->assignment,
                $employeeId,
                Auth::id()
            );

            $this->assignment->refresh();

            $this->successMessage = 'Hasil kerja berhasil disetujui.';

        } catch (\Illuminate\Validation\ValidationException $e) {

            $this->errorMessage = collect($e->errors())->flatten()->first()
                ?? 'Gagal approve hasil kerja.';

        }
    }

    public function rejectCompletion(AssignmentService $assignmentService): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        $this->validate([
            'rejectNotes' => ['required', 'string', 'min:5', 'max:2000'],
            'rejectMinutes' => ['nullable', 'integer', 'min:5', 'max:43200'],
        ]);

        try {

            $assignmentService->rejectCompletion(
                $this->assignment,
                $this->reviewingEmployeeId,
                Auth::id(),
                $this->rejectNotes,
                $this->rejectMinutes
            );

            $this->assignment->refresh();

            $this->successMessage = 'Hasil kerja ditolak, employee akan diminta revisi.';
            $this->reviewingEmployeeId = null;

        } catch (\Illuminate\Validation\ValidationException $e) {

            $this->errorMessage = collect($e->errors())->flatten()->first()
                ?? 'Gagal reject hasil kerja.';

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Missed Check Out Correction Review
    |--------------------------------------------------------------------------
    */

    public function approveCheckoutCorrection(int $correctionId, \App\Services\AttendanceCheckoutCorrectionService $service): void
    {
        $this->successMessage = null; $this->errorMessage = null;
        try {
            $correction = \App\Models\AttendanceCheckoutCorrection::findOrFail($correctionId);
            $service->approve(Auth::user(), $this->assignment, $correction);
            $this->assignment->refresh();
            $this->successMessage = 'Koreksi Check Out disetujui. Attendance sudah diperbarui.';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->errorMessage = collect($e->errors())->flatten()->first() ?? 'Gagal approve koreksi Check Out.';
        }
    }

    public function rejectCheckoutCorrection(int $correctionId, \App\Services\AttendanceCheckoutCorrectionService $service): void
    {
        $this->successMessage = null; $this->errorMessage = null;
        try {
            $correction = \App\Models\AttendanceCheckoutCorrection::findOrFail($correctionId);
            $service->reject(Auth::user(), $this->assignment, $correction);
            $this->assignment->refresh();
            $this->successMessage = 'Koreksi Check Out ditolak.';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->errorMessage = collect($e->errors())->flatten()->first() ?? 'Gagal reject koreksi Check Out.';
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

        $checkoutCorrectionsByEmployee = \App\Models\AttendanceCheckoutCorrection::query()
            ->where('assignment_id', $this->assignment->id)
            ->with('attendance')
            ->latest('id')
            ->get()
            ->groupBy('employee_id');

        return view('livewire.assignment.employee-manager', [
            'employees' => $this->assignment->employees()->with([
                'currentEmployment.position',
                'currentEmployment.office',
            ])->get(),
            'availableEmployees' => $availableEmployees,
            'checkoutCorrectionsByEmployee' => $checkoutCorrectionsByEmployee,
        ]);
    }
}
