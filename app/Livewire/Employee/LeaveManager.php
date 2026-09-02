<?php

namespace App\Livewire\Employee;

use App\Services\LeaveQuotaService;
use App\Services\LeaveRequestService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class LeaveManager extends Component
{
    use WithPagination;

    public string $type = '';
    public string $start_date = '';
    public string $end_date = '';
    public string $reason = '';

    #[Url(history: true)]
    public string $statusFilter = '';

    #[Url(history: true)]
    public string $typeFilter = '';

    public ?string $successMessage = null;

    protected $paginationTheme = 'tailwind';

    protected function rules(): array
    {
        return [
            'type' => ['required', 'in:Sakit,Acara,Cuti'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'max:1000'],
        ];
    }

    protected function messages(): array
    {
        return [
            'type.required' => 'Jenis izin wajib dipilih.',
            'type.in' => 'Jenis izin tidak valid.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.required' => 'Tanggal selesai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'reason.required' => 'Alasan izin wajib diisi.',
        ];
    }

    public function updated($property): void
    {
        if (in_array($property, ['statusFilter', 'typeFilter'], true)) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['statusFilter', 'typeFilter']);
        $this->resetPage();
    }

    public function submit(LeaveRequestService $leaveRequestService): void
    {
        $this->successMessage = null;
        $this->dispatch('action-loading');

        $data = $this->validate();

        try {
            $employee = Auth::user()->employee;
            $leaveRequestService->submit($employee, $data);

            $this->reset(['type', 'start_date', 'end_date', 'reason']);
            $this->successMessage = 'Pengajuan izin berhasil dikirim dan sedang menunggu review company.';
            $this->dispatch('action-complete');
        } catch (ValidationException $e) {
            $this->dispatch('action-loading-done');
            throw $e;
        }
    }

    public function render(LeaveRequestService $leaveRequestService, LeaveQuotaService $leaveQuotaService)
    {
        $employee = Auth::user()->employee;

        return view('livewire.employee.leave-manager', [
            'leaves' => $leaveRequestService->getForEmployee($employee, [
                'status' => $this->statusFilter,
                'type' => $this->typeFilter,
                'per_page' => 12,
            ]),
            'quota' => $leaveQuotaService->summary($employee, now()->year),
            'summary' => $leaveRequestService->summaryForEmployee($employee),
        ]);
    }
}
