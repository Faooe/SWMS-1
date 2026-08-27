<?php

namespace App\Services;

use App\Notifications\LeaveRequestReviewed;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Services\LeaveQuotaService;
use App\Notifications\LeaveRequestSubmitted;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class LeaveRequestService
{
    public function __construct(
        protected LeaveQuotaService $leaveQuotaService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Maksimal Durasi (hari) -- per Jenis
    |--------------------------------------------------------------------------
    |
    | Sebelumnya SATU angka (3 hari) dipakai untuk semua jenis izin.
    | Itu masuk akal untuk Sakit/Acara (izin mendadak, durasi pendek),
    | tapi tidak masuk akal untuk Cuti (cuti tahunan, wajar diajukan
    | lebih dari 3 hari). Sekarang durasi maksimal tergantung jenisnya.
    |
    */

    private const MAX_DURATION_DAYS = [
        'Cuti' => 12,
        'Sakit' => 3,
        'Acara' => 3,
    ];

    private const DEFAULT_MAX_DURATION_DAYS = 3;

    public const AUTO_REJECT_REASON = 'Ditolak otomatis karena batas tanggal izin telah terlewati tanpa persetujuan admin.';

    /**
     * Auto reject semua pengajuan yang masih Pending setelah end_date lewat.
     *
     * Dipanggil oleh scheduler/cron dan juga secara lazy sebelum list/review
     * supaya web & mobile tetap konsisten walaupun cron serverless terlambat.
     */
    public function autoRejectExpiredPending(?int $companyId = null, ?int $employeeId = null): int
    {
        $query = LeaveRequest::query()
            ->where('status', 'Pending')
            ->whereDate('end_date', '<', now()->toDateString());

        if ($companyId !== null) {
            $query->where('company_id', $companyId);
        }

        if ($employeeId !== null) {
            $query->where('employee_id', $employeeId);
        }

        return $query->update([
            'status' => 'Rejected',
            'approved_by' => null,
            'approved_at' => now(),
            'rejection_reason' => self::AUTO_REJECT_REASON,
            'updated_at' => now(),
        ]);
    }

    private function autoRejectIfExpired(LeaveRequest $leaveRequest): LeaveRequest
    {
        if (
            $leaveRequest->status === 'Pending'
            && $leaveRequest->end_date?->lt(now()->startOfDay())
        ) {
            $leaveRequest->update([
                'status' => 'Rejected',
                'approved_by' => null,
                'approved_at' => now(),
                'rejection_reason' => self::AUTO_REJECT_REASON,
            ]);

            return $leaveRequest->fresh();
        }

        return $leaveRequest;
    }

    private function maxDurationFor(string $type): int
    {
        return self::MAX_DURATION_DAYS[$type] ?? self::DEFAULT_MAX_DURATION_DAYS;
    }

    /*
    |--------------------------------------------------------------------------
    | List Izin Milik Employee
    |--------------------------------------------------------------------------
    */

    public function getForEmployee(
        Employee $employee,
        array $filters = []
    ): LengthAwarePaginator {

        $this->autoRejectExpiredPending(employeeId: $employee->id);

        $query = LeaveRequest::query()

            ->where('employee_id', $employee->id);

        if (!empty($filters['status'])) {

            $query->where('status', $filters['status']);

        }

        return $query

            ->orderByDesc('created_at')

            ->paginate($filters['per_page'] ?? 10);

    }

    /*
    |--------------------------------------------------------------------------
    | List Izin (Admin)
    |--------------------------------------------------------------------------
    */

    public function getAll(array $filters = []): LengthAwarePaginator
    {

        $companyId = auth()->user()?->company_id;
        if ($companyId) {
            $this->autoRejectExpiredPending(companyId: $companyId);
        }

        $query = LeaveRequest::query()

            ->forCurrentCompany()

            ->with(['employee', 'approver']);

        if (!empty($filters['search'])) {

            $search = $filters['search'];

            $query->whereHas('employee', function ($q) use ($search) {

                $q->where('full_name', 'like', "%{$search}%");

            });

        }

        if (!empty($filters['status'])) {

            $query->where('status', $filters['status']);

        }

        // Filter rentang tanggal (date_from/date_to) -- BARU, sebelumnya
        // web & API hanya bisa filter search & status. Semantik
        // "overlap": ambil pengajuan yang periodenya bersinggungan
        // dengan rentang filter, supaya cuti yang mulai sebelum
        // date_from tapi masih berlangsung sampai dalam rentang tetap
        // ikut kehitung -- bukan cuma yang start_date-nya persis di
        // dalam rentang.
        if (!empty($filters['date_from'])) {

            $query->whereDate('end_date', '>=', $filters['date_from']);

        }

        if (!empty($filters['date_to'])) {

            $query->whereDate('start_date', '<=', $filters['date_to']);

        }

        return $query

            ->orderByDesc('created_at')

            ->paginate($filters['per_page'] ?? 15);

    }

    /*
    |--------------------------------------------------------------------------
    | Ajukan Izin
    |--------------------------------------------------------------------------
    */

    public function submit(
        Employee $employee,
        array $data
    ): LeaveRequest {

        $startDate = Carbon::parse($data['start_date'])->startOfDay();

        $endDate = Carbon::parse($data['end_date'])->startOfDay();

        if ($endDate->lessThan($startDate)) {

            throw ValidationException::withMessages([
                'end_date' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            ]);

        }

        $duration = $startDate->diffInDays($endDate) + 1;

        $maxDuration = $this->maxDurationFor($data['type']);

        if ($duration > $maxDuration) {

            throw ValidationException::withMessages([
                'end_date' => "Durasi {$data['type']} maksimal {$maxDuration} hari.",
            ]);

        }

        // Kuota HANYA berlaku untuk Cuti -- Sakit/Acara tidak dibatasi
        // jatah tahunan, cukup dibatasi durasi per pengajuan di atas.
        // Dicek di sini (submit) supaya employee tidak bisa MENGAJUKAN
        // Cuti melebihi sisa jatah sejak awal -- dicek LAGI di approve()
        // supaya company admin juga tidak bisa MENYETUJUI melebihi sisa
        // jatah kalau ada beberapa pengajuan Pending yang totalnya
        // melebihi kuota (lihat catatan di approve()).
        if ($data['type'] === 'Cuti') {

            $year = $startDate->year;

            $remaining = $this->leaveQuotaService->remainingDays($employee, $year);

            if ($duration > $remaining) {

                throw ValidationException::withMessages([
                    'end_date' => "Sisa jatah Cuti tahun {$year} tinggal {$remaining} hari, "
                        . "tidak cukup untuk pengajuan {$duration} hari ini.",
                ]);

            }

        }

        $leaveRequest = LeaveRequest::create([

            'company_id' => $employee->company_id,

            'employee_id' => $employee->id,

            'type' => $data['type'],

            'start_date' => $startDate->toDateString(),

            'end_date' => $endDate->toDateString(),

            'reason' => $data['reason'],

            'status' => 'Pending',

        ]);

        $admins = User::query()
            ->companyAdminsOf($employee->company_id)
            ->get();

        Notification::send(
            $admins,
            new LeaveRequestSubmitted($leaveRequest)
        );

        return $leaveRequest;

    }

    /*
    |--------------------------------------------------------------------------
    | Approve Izin
    |--------------------------------------------------------------------------
    */

    public function approve(
        LeaveRequest $leaveRequest,
        User $approver
    ): LeaveRequest {

        $leaveRequest = $this->autoRejectIfExpired($leaveRequest);

        if (!$leaveRequest->canBeReviewed()) {

            throw ValidationException::withMessages([
                'status' => 'Pengajuan izin ini sudah diproses sebelumnya.',
            ]);

        }

        // Cek ULANG sisa jatah tepat sebelum di-approve (bukan cuma
        // waktu submit) -- soalnya bisa saja employee sudah mengajukan
        // beberapa Cuti sekaligus (semuanya masih Pending, jadi
        // lolos cek submit() karena belum ada yang Approved), lalu
        // admin approve satu-satu sampai total-nya kebablasan
        // melebihi kuota. Baris di bawah menutup celah itu.
        if ($leaveRequest->type === 'Cuti') {

            $year = $leaveRequest->start_date->year;

            $remaining = $this->leaveQuotaService->remainingDays(
                $leaveRequest->employee,
                $year,
                excludeLeaveRequestId: $leaveRequest->id
            );

            if ($leaveRequest->duration > $remaining) {

                throw ValidationException::withMessages([
                    'status' => "Sisa jatah Cuti {$leaveRequest->employee->full_name} "
                        . "tahun {$year} tinggal {$remaining} hari, tidak cukup untuk "
                        . "menyetujui pengajuan {$leaveRequest->duration} hari ini.",
                ]);

            }

        }

        DB::transaction(function () use ($leaveRequest, $approver) {

            $leaveRequest->update([

                'status' => 'Approved',

                'approved_by' => $approver->id,

                'approved_at' => now(),

            ]);

            $this->generateAttendanceRecords($leaveRequest);

        });

        $fresh = $leaveRequest->fresh(['employee.user']);
        $fresh->employee?->user?->notify(new LeaveRequestReviewed($fresh));

        return $fresh;

    }

    /*
    |--------------------------------------------------------------------------
    | Reject Izin
    |--------------------------------------------------------------------------
    */

    public function reject(
        LeaveRequest $leaveRequest,
        User $approver,
        ?string $reason = null
    ): LeaveRequest {

        $leaveRequest = $this->autoRejectIfExpired($leaveRequest);

        if (!$leaveRequest->canBeReviewed()) {

            throw ValidationException::withMessages([
                'status' => 'Pengajuan izin ini sudah diproses sebelumnya.',
            ]);

        }

        $leaveRequest->update([

            'status' => 'Rejected',

            'approved_by' => $approver->id,

            'approved_at' => now(),

            'rejection_reason' => $reason,

        ]);

        $fresh = $leaveRequest->fresh(['employee.user']);
        $fresh->employee?->user?->notify(new LeaveRequestReviewed($fresh));

        return $fresh;

    }

    /*
    |--------------------------------------------------------------------------
    | Generate Attendance Records (status: Leave / Permission)
    |--------------------------------------------------------------------------
    |
    | Dipanggil setelah izin di-approve. Sistem akan membuat satu baris
    | data attendance untuk setiap tanggal dalam rentang izin, sehingga
    | employee aman dari sapuan Auto-Absent di malam hari.
    |
    | PENTING: sebelumnya attendance_status di-hardcode selalu
    | 'Permission' apapun jenis izinnya -- akibatnya status 'Leave' di
    | enum attendance_status TIDAK PERNAH terpakai sama sekali (kartu
    | statistik "Leave" di halaman Attendance selalu 0). Sekarang
    | dipetakan sesuai jenisnya: Cuti -> Leave, Sakit/Acara -> Permission.
    |
    */

    private function attendanceStatusFor(string $type): string
    {
        return $type === 'Cuti' ? 'Leave' : 'Permission';
    }

    private function generateAttendanceRecords(LeaveRequest $leaveRequest): void
    {

        $employee = $leaveRequest->employee;

        $officeId = $employee->currentEmployment?->office_id;

        $attendanceStatus = $this->attendanceStatusFor($leaveRequest->type);

        $period = $leaveRequest->start_date->toImmutable()
            ->daysUntil($leaveRequest->end_date->toImmutable()->addDay());

        foreach ($period as $date) {

            Attendance::updateOrCreate(

                [

                    'employee_id' => $employee->id,

                    'attendance_date' => $date->toDateString(),

                ],

                [

                    'company_id' => $employee->company_id,

                    'office_id' => $officeId,

                    'attendance_type' => 'OFFICE',

                    'attendance_status' => $attendanceStatus,

                    'is_checked_in' => false,

                    'is_checked_out' => false,

                    'notes' => trim(
                        $leaveRequest->type . ': ' . $leaveRequest->reason
                    ),

                ]

            );

        }

    }
}