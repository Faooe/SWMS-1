<?php

namespace App\Livewire\Leave;

use App\Models\LeaveRequest;
use App\Services\LeaveRequestService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Manager extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $status = '';

    public ?string $successMessage = null;

    public ?string $errorMessage = null;

    protected $paginationTheme = 'tailwind';

    public function updated($property): void
    {
        if (in_array($property, ['search', 'status'])) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'status']);
        $this->resetPage();
    }

    public function approve(int $leaveId, LeaveRequestService $leaveRequestService): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        $this->dispatch('action-loading');

        try {

            $leave = LeaveRequest::where('company_id', Auth::user()->company_id)->findOrFail($leaveId);

            $leaveRequestService->approve($leave, Auth::user());

            $this->successMessage = 'Pengajuan izin berhasil disetujui.';
            $this->dispatch('action-complete');

        } catch (ValidationException $e) {

            $this->errorMessage = collect($e->errors())->flatten()->first();
            $this->dispatch('action-loading-done');

        } catch (\Throwable $e) {

            report($e);

            $this->errorMessage = 'Gagal memproses pengajuan izin.';
            $this->dispatch('action-loading-done');

        }
    }

    public function reject(int $leaveId, LeaveRequestService $leaveRequestService): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        $this->dispatch('action-loading');

        try {

            $leave = LeaveRequest::where('company_id', Auth::user()->company_id)->findOrFail($leaveId);

            $leaveRequestService->reject($leave, Auth::user());

            $this->successMessage = 'Pengajuan izin ditolak.';
            $this->dispatch('action-complete');

        } catch (ValidationException $e) {

            $this->errorMessage = collect($e->errors())->flatten()->first();
            $this->dispatch('action-loading-done');

        } catch (\Throwable $e) {

            report($e);

            $this->errorMessage = 'Gagal memproses pengajuan izin.';
            $this->dispatch('action-loading-done');

        }
    }

    public function render(LeaveRequestService $leaveRequestService)
    {
        return view('livewire.leave.manager', [
            'leaves' => $leaveRequestService->getAll([
                'search' => $this->search,
                'status' => $this->status,
                'per_page' => 15,
            ]),
        ]);
    }
}
