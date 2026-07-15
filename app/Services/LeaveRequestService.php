<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LeaveRequestService
{
    /*
    |--------------------------------------------------------------------------
    | Maksimal Durasi Izin (hari)
    |--------------------------------------------------------------------------
    */

    private const MAX_DURATION_DAYS = 3;

    /*
    |--------------------------------------------------------------------------
    | List Izin Milik Employee
    |--------------------------------------------------------------------------
    */

    public function getForEmployee(
        Employee $employee,
        array $filters = []
    ): LengthAwarePaginator {

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

        if ($duration > self::MAX_DURATION_DAYS) {

            throw ValidationException::withMessages([
                'end_date' => 'Durasi izin maksimal ' . self::MAX_DURATION_DAYS . ' hari.',
            ]);

        }

        return LeaveRequest::create([

            'company_id' => $employee->company_id,

            'employee_id' => $employee->id,

            'type' => $data['type'],

            'start_date' => $startDate->toDateString(),

            'end_date' => $endDate->toDateString(),

            'reason' => $data['reason'],

            'status' => 'Pending',

        ]);

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

        if (!$leaveRequest->canBeReviewed()) {

            throw ValidationException::withMessages([
                'status' => 'Pengajuan izin ini sudah diproses sebelumnya.',
            ]);

        }

        DB::transaction(function () use ($leaveRequest, $approver) {

            $leaveRequest->update([

                'status' => 'Approved',

                'approved_by' => $approver->id,

                'approved_at' => now(),

            ]);

            $this->generateAttendanceRecords($leaveRequest);

        });

        return $leaveRequest->fresh();

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

        return $leaveRequest->fresh();

    }

    /*
    |--------------------------------------------------------------------------
    | Generate Attendance Records (status: Permission)
    |--------------------------------------------------------------------------
    |
    | Dipanggil setelah izin di-approve. Sistem akan membuat satu baris
    | data attendance untuk setiap tanggal dalam rentang izin, sehingga
    | employee aman dari sapuan Auto-Absent di malam hari.
    |
    */

    private function generateAttendanceRecords(LeaveRequest $leaveRequest): void
    {

        $employee = $leaveRequest->employee;

        $officeId = $employee->currentEmployment?->office_id;

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

                    'attendance_status' => 'Permission',

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
