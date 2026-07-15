<?php

namespace App\Services\Attendance;

use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Support\Carbon as SupportCarbon;

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

        $count = 0;

        Employee::query()

            ->active()

            ->with(['currentEmployment'])

            ->chunkById(100, function ($employees) use ($today, &$count) {

                foreach ($employees as $employee) {

                    if ($this->hasAttendanceRecordToday($employee, $today)) {

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

        Attendance::create([

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

    }
}
