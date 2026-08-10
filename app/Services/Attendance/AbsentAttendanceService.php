<?php

namespace App\Services\Attendance;

use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use App\Notifications\EmployeeMarkedAbsent;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\Notification;

class AbsentAttendanceService
{
    /*
    |--------------------------------------------------------------------------
    | Tandai Karyawan Absent (Alpa/Mangkir)
    |--------------------------------------------------------------------------
    |
    | Karyawan yang terjadwal masuk (baik jadwal kantor normal maupun
    | assignment aktif) tetapi tidak memiliki record attendance sama
    | sekali pada suatu hari -- artinya tidak check-in dan tidak memiliki
    | Permission yang disetujui -- akan dibuatkan baris baru dengan status
    | Absent.
    |
    | Sebelumnya cuma cek HARI INI (today()). Itu cocok kalau job-nya jalan
    | tiap 15 menit (Schedule::everyFifteenMinutes()) -- tapi di Vercel
    | (serverless, lihat routes/cron.php & CronController), job ini cuma
    | bisa dipicu 1x SEHARI lewat Vercel Cron (batasan plan Hobby). Kalau
    | shift-end seorang karyawan lebih siang dari jam cron jalan (mis. shift
    | pulang jam 22:00 tapi cron cuma jalan jam 09:00 UTC), karyawan itu
    | akan "kelewat" terus setiap hari -- besoknya $today sudah pindah ke
    | hari berikutnya, jadi hari yang seharusnya ditandai Absent itu
    | keburu terlewat SELAMANYA.
    |
    | Makanya sekarang cek MUNDUR beberapa hari (default 3 hari termasuk
    | hari ini), bukan cuma hari ini -- supaya kalaupun cron sempat
    | terlewat/telat semalam-dua-malam, tetap ke-cover di run berikutnya.
    | Hari-hari SEBELUM hari ini otomatis dianggap sudah lewat sepenuhnya
    | (tidak perlu cek isPastShiftEnd lagi, karena harinya sendiri sudah
    | berakhir); cuma HARI INI yang masih dicek batas shift-end-nya
    | (isPastShiftEnd) supaya karyawan yang jadwalnya belum berakhir hari
    | ini tidak keburu ditandai Absent.
    |
    */

    public function markAbsentForRecentDays(int $lookbackDays = 3): int
    {

        $count = 0;

        for ($daysAgo = $lookbackDays - 1; $daysAgo >= 0; $daysAgo--) {

            $count += $this->markAbsentForDate(
                today()->subDays($daysAgo),
                isToday: $daysAgo === 0
            );

        }

        return $count;

    }

    /**
     * @deprecated Pakai markAbsentForRecentDays() -- dibiarkan supaya
     * pemanggil lama (kalau ada) tidak langsung patah, tapi perilakunya
     * sekarang cuma mengecek hari ini saja (lookback 1 hari).
     */
    public function markAbsentForToday(): int
    {

        return $this->markAbsentForRecentDays(1);

    }

    private function markAbsentForDate(SupportCarbon $date, bool $isToday): int
    {

        $now = now();

        $count = 0;

        Employee::query()

            ->active()

            ->whereDoesntHave('user.role', function ($query) {

                $query->whereIn('code', [
                    'SUPER_ADMIN',
                    'PLATFORM_ADMIN',
                ]);

            })

            ->with(['currentEmployment.office', 'currentEmployment.shift'])

            ->chunkById(100, function ($employees) use ($date, $now, $isToday, &$count) {

                foreach ($employees as $employee) {

                    if ($this->hasAttendanceRecordToday($employee, $date)) {

                        continue;

                    }

                    if ($isToday && !$this->isPastShiftEnd($employee, $now)) {

                        // Jadwal kerja (shift) karyawan ini belum berakhir
                        // HARI INI, jangan tandai Absent dulu. Untuk
                        // tanggal-tanggal SEBELUM hari ini, cek ini
                        // dilewati -- harinya sendiri sudah pasti berakhir.
                        continue;

                    }

                    $assignment = $this->getActiveAssignmentToday(
                        $employee,
                        $date
                    );

                    if ($assignment) {

                        $this->createAbsent(
                            $employee,
                            $date,
                            'ASSIGNMENT',
                            $assignment->office_id,
                            $assignment->id
                        );

                        $count++;

                        continue;

                    }

                    $officeId = $employee->currentEmployment?->office_id;

                    if (!$officeId) {

                        // Tidak punya office maupun assignment aktif -> tidak terjadwal, skip.
                        continue;

                    }

                    $this->createAbsent(
                        $employee,
                        $date,
                        'OFFICE',
                        $officeId,
                        null
                    );

                    $count++;

                }

            });

        return $count;

    }

    /*
    |--------------------------------------------------------------------------
    | Cek Apakah Jadwal Kerja (Shift) Karyawan Sudah Berakhir
    |--------------------------------------------------------------------------
    |
    | Karyawan baru ditandai Absent setelah jam pulang (shift end) miliknya
    | terlewati -- default 17:00 kalau karyawan tidak punya shift spesifik.
    | Ini supaya karyawan yang jadwalnya masih berjalan (misal shift siang)
    | tidak keliru ditandai Absent sebelum waktunya.
    |
    */

    private function isPastShiftEnd(
        Employee $employee,
        SupportCarbon $now
    ): bool {

        $shiftEnd = $employee->currentEmployment?->shift?->end_time
            ?? '17:00:00';

        $cutoff = today()->setTimeFromTimeString($shiftEnd);

        return $now->greaterThanOrEqualTo($cutoff);

    }

    /*
    |--------------------------------------------------------------------------
    | Cek Apakah Sudah Ada Record Attendance Hari Ini
    |--------------------------------------------------------------------------
    */

    private function hasAttendanceRecordToday(
        Employee $employee,
        SupportCarbon $date
    ): bool {

        return Attendance::query()

            ->where('employee_id', $employee->id)

            ->whereDate('attendance_date', $date)

            ->exists();

    }

    /*
    |--------------------------------------------------------------------------
    | Cari Assignment Aktif Hari Ini
    |--------------------------------------------------------------------------
    */

    private function getActiveAssignmentToday(
        Employee $employee,
        SupportCarbon $date
    ): ?Assignment {

        return Assignment::query()

            ->whereHas('employees', function ($query) use ($employee) {

                $query

                    ->where('employees.id', $employee->id)

                    ->whereIn('assignment_employees.status', [

                        'Assigned',

                        'Accepted',

                        'In Progress',

                    ]);

            })

            ->whereDate('start_datetime', '<=', $date)

            ->whereDate('end_datetime', '>=', $date)

            ->whereIn('status', [

                'Assigned',

                'In Progress',

            ])

            ->first();

    }

    /*
    |--------------------------------------------------------------------------
    | Buat Record Attendance Absent
    |--------------------------------------------------------------------------
    */

    private function createAbsent(
        Employee $employee,
        SupportCarbon $date,
        string $type,
        ?int $officeId,
        ?int $assignmentId
    ): void {

        $attendance = Attendance::create([

            'employee_id' => $employee->id,

            'company_id' => $employee->company_id,

            'office_id' => $officeId,

            'assignment_id' => $assignmentId,

            'attendance_type' => $type,

            'attendance_date' => $date->toDateString(),

            'attendance_status' => 'Absent',

            'is_checked_in' => false,

            'is_checked_out' => false,

        ]);

        $admins = User::query()
            ->companyAdminsOf($employee->company_id)
            ->get();

        Notification::send(
            $admins,
            new EmployeeMarkedAbsent($attendance)
        );

    }
}