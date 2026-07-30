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
    | Tandai Karyawan Absent (Alpa/Mangkir) untuk Hari Ini
    |--------------------------------------------------------------------------
    |
    | Dijalankan via scheduled command setiap tengah malam (23:59). Karyawan
    | yang terjadwal masuk (baik jadwal kantor normal maupun assignment aktif)
    | tetapi tidak memiliki record attendance sama sekali hari ini -- artinya
    | tidak check-in dan tidak memiliki Permission yang disetujui -- akan
    | dibuatkan baris baru dengan status Absent.
    |
    */

    public function markAbsentForToday(): int
    {

        $today = today();

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

            ->chunkById(100, function ($employees) use ($today, $now, &$count) {

                foreach ($employees as $employee) {

                    if ($this->hasAttendanceRecordToday($employee, $today)) {

                        continue;

                    }

                    if (!$this->isPastShiftEnd($employee, $now)) {

                        // Jadwal kerja (shift) karyawan ini belum berakhir,
                        // jangan tandai Absent dulu.
                        continue;

                    }

                    $assignment = $this->getActiveAssignmentToday(
                        $employee,
                        $today
                    );

                    if ($assignment) {

                        $this->createAbsent(
                            $employee,
                            $today,
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
                        $today,
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
        SupportCarbon $today
    ): bool {

        return Attendance::query()

            ->where('employee_id', $employee->id)

            ->whereDate('attendance_date', $today)

            ->exists();

    }

    /*
    |--------------------------------------------------------------------------
    | Cari Assignment Aktif Hari Ini
    |--------------------------------------------------------------------------
    */

    private function getActiveAssignmentToday(
        Employee $employee,
        SupportCarbon $today
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

            ->whereDate('start_datetime', '<=', $today)

            ->whereDate('end_datetime', '>=', $today)

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
        SupportCarbon $today,
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

            'attendance_date' => $today->toDateString(),

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