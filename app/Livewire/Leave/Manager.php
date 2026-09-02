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
    public string $status = 'Pending';

    #[Url(history: true)]
    public string $type = '';

    #[Url(history: true)]
    public string $dateFrom = '';

    #[Url(history: true)]
    public string $dateTo = '';

    public ?int $rejectingLeaveId = null;
    public string $rejectionReason = '';

    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    protected $paginationTheme = 'tailwind';

    public function updated($property): void
    {
        if (in_array($property, ['search', 'status', 'type', 'dateFrom', 'dateTo'], true)) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->status = 'Pending';
        $this->type = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->resetPage();
    }

    public function showAll(): void
    {
        $this->reset(['search', 'status', 'type', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function startReject(int $leaveId): void
    {
        $leave = LeaveRequest::query()
            ->where('company_id', Auth::user()->company_id)
            ->findOrFail($leaveId);

        if (!$leave->canBeReviewed()) {
            $this->errorMessage = 'Pengajuan izin ini sudah diproses.';
            return;
        }

        $this->rejectingLeaveId = $leaveId;
        $this->rejectionReason = '';
        $this->resetValidation('rejectionReason');
    }

    public function cancelReject(): void
    {
        $this->rejectingLeaveId = null;
        $this->rejectionReason = '';
        $this->resetValidation('rejectionReason');
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

    public function confirmReject(LeaveRequestService $leaveRequestService): void
    {
        $this->validate([
            'rejectionReason' => ['nullable', 'string', 'max:1000'],
        ]);

        if (!$this->rejectingLeaveId) {
            return;
        }

        $this->successMessage = null;
        $this->errorMessage = null;
        $this->dispatch('action-loading');

        try {
            $leave = LeaveRequest::where('company_id', Auth::user()->company_id)
                ->findOrFail($this->rejectingLeaveId);

            $leaveRequestService->reject(
                $leave,
                Auth::user(),
                trim($this->rejectionReason) ?: null
            );

            $this->successMessage = 'Pengajuan izin berhasil ditolak.';
            $this->cancelReject();
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
                'type' => $this->type,
                'date_from' => $this->dateFrom,
                'date_to' => $this->dateTo,
                'per_page' => 15,
            ]),
            'summary' => $leaveRequestService->summaryForCompany(Auth::user()->company_id),
        ]);
    }
}
