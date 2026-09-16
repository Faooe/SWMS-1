<?php

namespace App\Livewire\Leave;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Services\LeaveQuotaService;
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

    public string $quotaTarget = 'selected';

    /** @var array<int, int|string> */
    public array $quotaEmployeeIds = [];

    public int $quotaYear;

    public int $quotaTotalDays = LeaveQuotaService::DEFAULT_ANNUAL_QUOTA_DAYS;

    protected $paginationTheme = 'tailwind';

    public function mount(): void
    {
        $this->quotaYear = now()->year;
    }

    public function selectAllQuotaEmployees(): void
    {
        $this->quotaTarget = 'selected';
        $this->quotaEmployeeIds = Employee::query()
            ->where('company_id', Auth::user()->company_id)
            ->where('is_active', true)
            ->orderBy('full_name')
            ->pluck('id')
            ->map(static fn ($id) => (int) $id)
            ->all();
    }

    public function clearQuotaEmployees(): void
    {
        $this->quotaEmployeeIds = [];
        $this->quotaTarget = 'selected';
    }

    public function saveQuota(LeaveQuotaService $leaveQuotaService): void
    {
        $this->resetErrorBag();
        $rules = [
            'quotaTarget' => ['required', 'in:selected,all'],
            'quotaEmployeeIds' => ['array'],
            'quotaYear' => ['required', 'integer', 'min:2000', 'max:2100'],
            'quotaTotalDays' => ['required', 'integer', 'min:0', 'max:255'],
        ];

        if ($this->quotaTarget === 'selected') {
            $rules['quotaEmployeeIds'][] = 'required';
            $rules['quotaEmployeeIds'][] = 'min:1';
        }

        $this->validate($rules, [
            'quotaEmployeeIds.required' => 'Pilih minimal satu employee.',
            'quotaEmployeeIds.min' => 'Pilih minimal satu employee.',
            'quotaTotalDays.required' => 'Jumlah kuota wajib diisi.',
            'quotaTotalDays.min' => 'Kuota tidak boleh kurang dari 0 hari.',
        ]);

        $employeeQuery = Employee::query()
            ->where('company_id', Auth::user()->company_id)
            ->where('is_active', true);

        if ($this->quotaTarget === 'selected') {
            $ids = collect($this->quotaEmployeeIds)
                ->map(static fn ($id) => (int) $id)
                ->filter(static fn (int $id) => $id > 0)
                ->unique()
                ->values();

            $employees = $employeeQuery->whereIn('id', $ids)->get(['id', 'full_name']);
            if ($employees->count() !== $ids->count()) {
                $this->addError('quotaEmployeeIds', 'Ada employee yang tidak valid atau tidak aktif.');

                return;
            }
        } else {
            $employees = $employeeQuery->get(['id', 'full_name']);
        }

        if ($employees->isEmpty()) {
            $this->addError('quotaEmployeeIds', 'Belum ada employee aktif yang bisa diatur.');

            return;
        }

        $updated = $leaveQuotaService->setTotalDaysForEmployees(
            $employees,
            $this->quotaYear,
            $this->quotaTotalDays
        );

        $targetLabel = $this->quotaTarget === 'all'
            ? 'semua employee aktif'
            : "{$updated} employee terpilih";
        $this->successMessage = "Kuota {$targetLabel} berhasil diperbarui menjadi {$this->quotaTotalDays} hari untuk {$this->quotaYear}.";
        $this->dispatch('quota-updated', year: $this->quotaYear, employeeCount: $updated);
    }

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

        if (! $leave->canBeReviewed()) {
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

        if (! $this->rejectingLeaveId) {
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
                'per_page' => 10,
            ]),
            'summary' => $leaveRequestService->summaryForCompany(Auth::user()->company_id),
            'quotaEmployees' => Employee::query()
                ->where('company_id', Auth::user()->company_id)
                ->where('is_active', true)
                ->orderBy('full_name')
                ->get(['id', 'full_name', 'employee_number']),
        ]);
    }
}
