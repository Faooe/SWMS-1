<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\LeaveQuota;
use App\Models\LeaveRequest;

/*
|--------------------------------------------------------------------------
| Leave Quota Service
|--------------------------------------------------------------------------
|
| Mengurus jatah Cuti tahunan (BUKAN Sakit/Acara -- dua jenis izin itu
| tidak pakai kuota sama sekali, cukup dibatasi durasi per pengajuan,
| lihat LeaveRequestService::MAX_DURATION_DAYS).
|
| Prinsip desain:
| - "total_days" (jatah) per employee per tahun BOLEH disesuaikan admin
|   (baris di tabel leave_quotas), tapi kalau tidak pernah disesuaikan,
|   otomatis pakai DEFAULT_ANNUAL_QUOTA_DAYS -- lihat totalDaysFor().
| - "used_days" (terpakai) TIDAK disimpan sebagai counter -- selalu
|   dihitung ulang dari SUM durasi LeaveRequest tipe 'Cuti' berstatus
|   'Approved' pada tahun terkait. Ini bikin angkanya SELALU akurat
|   walau ada race condition/reject/dsb -- sumber kebenarannya cuma
|   satu tabel (leave_requests), bukan dua tabel yang harus disinkronkan
|   manual.
| - "Reset per tahun" otomatis terjadi karena kuota di-scope per tahun --
|   begitu tahun baru mulai, usedDays() untuk tahun itu otomatis 0
|   (belum ada Cuti approved di tahun itu) dan totalDays() balik ke
|   default/penyesuaian khusus tahun itu. Tidak perlu cron reset apapun.
|
*/

class LeaveQuotaService
{
    public const DEFAULT_ANNUAL_QUOTA_DAYS = 12;

    /*
    |--------------------------------------------------------------------------
    | Total Jatah (hari) -- untuk satu tahun
    |--------------------------------------------------------------------------
    */

    public function totalDaysFor(Employee $employee, int $year): int
    {
        return LeaveQuota::query()
            ->where('employee_id', $employee->id)
            ->where('year', $year)
            ->value('total_days') ?? self::DEFAULT_ANNUAL_QUOTA_DAYS;
    }

    /*
    |--------------------------------------------------------------------------
    | Terpakai (hari) -- SUM durasi Cuti yang sudah Approved
    |--------------------------------------------------------------------------
    |
    | $excludeLeaveRequestId dipakai waktu approve() mengecek ULANG
    | jatah tepat sebelum sebuah pengajuan disetujui -- supaya pengajuan
    | yang sedang dicek tidak dihitung dobel kalau (secara teori) sudah
    | keburu berstatus Approved duluan.
    |
    */

    public function usedDays(Employee $employee, int $year, ?int $excludeLeaveRequestId = null): int
    {
        return LeaveRequest::query()
            ->where('employee_id', $employee->id)
            ->where('type', 'Cuti')
            ->where('status', 'Approved')
            ->whereYear('start_date', $year)
            ->when(
                $excludeLeaveRequestId,
                fn ($query) => $query->where('id', '!=', $excludeLeaveRequestId)
            )
            ->get()
            ->sum(fn (LeaveRequest $leaveRequest) => $leaveRequest->duration);
    }

    /*
    |--------------------------------------------------------------------------
    | Sisa Jatah (hari)
    |--------------------------------------------------------------------------
    */

    public function remainingDays(Employee $employee, int $year, ?int $excludeLeaveRequestId = null): int
    {
        $remaining = $this->totalDaysFor($employee, $year)
            - $this->usedDays($employee, $year, $excludeLeaveRequestId);

        return max(0, $remaining);
    }

    /*
    |--------------------------------------------------------------------------
    | Ringkasan Lengkap -- dipakai endpoint API (employee & admin)
    |--------------------------------------------------------------------------
    */

    public function summary(Employee $employee, int $year): array
    {

        $total = $this->totalDaysFor($employee, $year);

        $used = $this->usedDays($employee, $year);

        return [

            'year' => $year,

            'total_days' => $total,

            'used_days' => $used,

            'remaining_days' => max(0, $total - $used),

        ];

    }

    /*
    |--------------------------------------------------------------------------
    | Sesuaikan Jatah (Admin) -- mis. employee dapat tambahan cuti
    |--------------------------------------------------------------------------
    |
    | Hanya untuk tahun yang disebutkan -- tidak memengaruhi tahun lain,
    | sesuai desain year-scoped di atas.
    |
    */

    public function setTotalDays(Employee $employee, int $year, int $totalDays): LeaveQuota
    {
        return LeaveQuota::updateOrCreate(
            [
                'employee_id' => $employee->id,
                'year' => $year,
            ],
            [
                'total_days' => $totalDays,
            ]
        );
    }
}
