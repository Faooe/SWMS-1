<?php

namespace App\Livewire\Employee;

use App\Services\LeaveRequestService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class LeaveManager extends Component
{
    use WithPagination;

    public string $type = '';

    public string $start_date = '';

    public string $end_date = '';

    public string $reason = '';

    public ?string $successMessage = null;

    protected $paginationTheme = 'tailwind';

    protected function rules(): array
    {
        return [
            'type' => ['required', 'in:Sakit,Acara'],
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

    public function submit(LeaveRequestService $leaveRequestService): void
    {
        $this->successMessage = null;

        $this->dispatch('action-loading');

        $data = $this->validate();

        try {

            $employee = Auth::user()->employee;

            $leaveRequestService->submit($employee, $data);

            $this->reset(['type', 'start_date', 'end_date', 'reason']);

            $this->successMessage = 'Pengajuan izin berhasil dikirim, menunggu persetujuan admin.';

            $this->dispatch('action-complete');

        } catch (ValidationException $e) {

            $this->dispatch('action-loading-done');

            throw $e;

        }
    }

    public function render(LeaveRequestService $leaveRequestService)
    {
        $employee = Auth::user()->employee;

        return view('livewire.employee.leave-manager', [
            'leaves' => $leaveRequestService->getForEmployee($employee, [
                'per_page' => 10,
            ]),
        ]);
    }
}
