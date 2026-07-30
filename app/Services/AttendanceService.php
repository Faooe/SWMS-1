<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\AssignmentEmployee;
use App\Models\AssignmentLog;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AttendanceService extends BaseService
{
    /**
     * Calculate distance between two coordinates using Haversine Formula.
     *
     * @return float Distance in meters.
     */
    public function calculateDistance(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {

        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a =
            sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($dLon / 2) *
            sin($dLon / 2);

        $c = 2 * atan2(
            sqrt($a),
            sqrt(1 - $a)
        );

        return $earthRadius * $c;
    }

    /**
     * Check whether employee is inside office radius.
     */
    public function isInsideRadius(
        float $employeeLatitude,
        float $employeeLongitude,
        float $officeLatitude,
        float $officeLongitude,
        int $radius
    ): bool {

        $distance = $this->calculateDistance(
            $employeeLatitude,
            $employeeLongitude,
            $officeLatitude,
            $officeLongitude
        );

        return $distance <= $radius;
    }

    /**
     * Calculate attendance status.
     *
     * NOTE: pakai now() / today() (helper Laravel) supaya otomatis
     * mengikuti config('app.timezone'), bukan Carbon::now() yang
     * defaultnya UTC kalau tidak eksplisit di-set timezone-nya.
     */
    public function calculateLate(
        string $shiftStart,
        int $tolerance
    ): array {

        $now = now();

        $shiftStartTime = today()
            ->setTimeFromTimeString($shiftStart);

        $allowedTime = $shiftStartTime
            ->copy()
            ->addMinutes($tolerance);

        if ($now->lessThanOrEqualTo($allowedTime)) {

            return [
                'status' => 'Present',
                'late_minutes' => 0,
            ];
        }

        return [
            'status' => 'Late',
            'late_minutes' => $shiftStartTime->diffInMinutes($now),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Smart Check In
    |--------------------------------------------------------------------------
    |
    | Dispatcher: kalau employee sedang punya assignment aktif (statusnya
    | belum 'Completed'), arahkan ke checkInAssignment(). Kalau tidak,
    | jalankan checkIn() (office) seperti biasa.
    |
    */

    public function smartCheckIn(
        User $user,
        array $data
    ): Attendance {

        $employee = $user->employee;

        if (!$employee) {

            throw ValidationException::withMessages([
                'employee' => [
                    'Data karyawan tidak ditemukan.'
                ]
            ]);

        }

        $currentAssignment = $employee->currentAssignment;

        if (
            $currentAssignment
            &&
            in_array($currentAssignment->status, [
                'Assigned',
                'Accepted',
                'In Progress',
            ])
        ) {

            return $this->checkInAssignment($user, $data);

        }

        return $this->checkIn($user, $data);
    }

    /**
     * Employee Check In (Office).
     */
    public function checkIn(
        User $user,
        array $data
    ): Attendance {

        /*
        |--------------------------------------------------------------------------
        | Employee
        |--------------------------------------------------------------------------
        */

        $employee = $user->employee;

        if (!$employee) {

            throw ValidationException::withMessages([
                'employee' => [
                    'Data karyawan tidak ditemukan.'
                ]
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Employment
        |--------------------------------------------------------------------------
        */

        $employment = $employee->currentEmployment;

        if (!$employment) {

            throw ValidationException::withMessages([
                'employment' => [
                    'Penempatan kerja belum tersedia.'
                ]
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Office
        |--------------------------------------------------------------------------
        */

        $office = $employment->office;

        if (!$office) {

            throw ValidationException::withMessages([
                'office' => [
                    'Office tidak ditemukan.'
                ]
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Shift
        |--------------------------------------------------------------------------
        */

        $shift = $employment->shift;

        if (!$shift) {

            throw ValidationException::withMessages([
                'shift' => [
                    'Shift tidak ditemukan.'
                ]
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Already Check In Today
        |--------------------------------------------------------------------------
        */

        $alreadyCheckedIn = Attendance::query()
            ->where('employee_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->exists();

        if ($alreadyCheckedIn) {

            throw ValidationException::withMessages([
                'attendance' => [
                    'Anda sudah melakukan check in hari ini.'
                ]
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Distance
        |--------------------------------------------------------------------------
        */

        $distance = $this->calculateDistance(

            $data['latitude'],

            $data['longitude'],

            $office->latitude,

            $office->longitude

        );

        $locationVerified = $distance <= $office->radius;

        /*
        |--------------------------------------------------------------------------
        | GPS Validation
        |--------------------------------------------------------------------------
        */

        if (!$locationVerified) {

            throw ValidationException::withMessages([
                'location' => [
                    'Anda berada di luar radius kantor.'
                ]
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Late Detection
        |--------------------------------------------------------------------------
        */

        $late = $this->calculateLate(

            $shift->start_time,

            $shift->late_tolerance

        );

        /*
        |--------------------------------------------------------------------------
        | Save Attendance
        |--------------------------------------------------------------------------
        */

        return DB::transaction(function () use (
            $employee,
            $office,
            $shift,
            $data,
            $late,
            $distance,
            $locationVerified
        ) {

            return Attendance::create([

                /*
                |--------------------------------------------------------------------------
                | Relation
                |--------------------------------------------------------------------------
                */

                'company_id' => $employee->company_id,

                'employee_id' => $employee->id,

                'office_id' => $office->id,

                'assignment_id' => null,

                'shift_id' => $shift->id,

                /*
                |--------------------------------------------------------------------------
                | Type
                |--------------------------------------------------------------------------
                */

                'attendance_type' => 'OFFICE',

                /*
                |--------------------------------------------------------------------------
                | Date
                |--------------------------------------------------------------------------
                */

                'attendance_date' => today(),

                /*
                |--------------------------------------------------------------------------
                | Check In
                |--------------------------------------------------------------------------
                */

                'check_in_time' => now(),

                'check_in_latitude' => $data['latitude'],

                'check_in_longitude' => $data['longitude'],

                'check_in_distance' => $distance,

                /*
                |--------------------------------------------------------------------------
                | GPS Validation
                |--------------------------------------------------------------------------
                */

                'allowed_radius' => $office->radius,

                'location_verified' => $locationVerified,

                /*
                |--------------------------------------------------------------------------
                | Status
                |--------------------------------------------------------------------------
                */

                'attendance_status' => $late['status'],

                'late_minutes' => $late['late_minutes'],

                /*
                |--------------------------------------------------------------------------
                | Progress
                |--------------------------------------------------------------------------
                */

                'is_checked_in' => true,

                'is_checked_out' => false,

                /*
                |--------------------------------------------------------------------------
                | Notes
                |--------------------------------------------------------------------------
                */

                'notes' => $data['notes'] ?? null,

            ]);

        });
    }

    /**
     * Employee Check In (Assignment).
     *
     * GPS hanya divalidasi kalau assignment punya titik koordinat
     * (latitude/longitude/radius) yang di-set. Kalau tidak, dianggap
     * lokasi bebas (field work) dan location_verified = false tanpa
     * memblokir check-in.
     */
    public function checkInAssignment(
        User $user,
        array $data
    ): Attendance {

        $employee = $user->employee;

        if (!$employee) {

            throw ValidationException::withMessages([
                'employee' => [
                    'Data karyawan tidak ditemukan.'
                ]
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Assignment Employee (pivot) -> Assignment
        |--------------------------------------------------------------------------
        */

        $assignmentEmployee = $employee->currentAssignment;

        if (!$assignmentEmployee || $assignmentEmployee->status === 'Completed') {

            throw ValidationException::withMessages([
                'assignment' => [
                    'Assignment aktif tidak ditemukan.'
                ]
            ]);

        }

        $assignment = $assignmentEmployee->assignment;

        if (!$assignment) {

            throw ValidationException::withMessages([
                'assignment' => [
                    'Data assignment tidak ditemukan.'
                ]
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Employment / Shift (tetap dipakai untuk keterlambatan)
        |--------------------------------------------------------------------------
        */

        $employment = $employee->currentEmployment;

        if (!$employment) {

            throw ValidationException::withMessages([
                'employment' => [
                    'Penempatan kerja belum tersedia.'
                ]
            ]);

        }

        $shift = $employment->shift;

        if (!$shift) {

            throw ValidationException::withMessages([
                'shift' => [
                    'Shift tidak ditemukan.'
                ]
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Already Check In Today
        |--------------------------------------------------------------------------
        */

        $alreadyCheckedIn = Attendance::query()
            ->where('employee_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->exists();

        if ($alreadyCheckedIn) {

            throw ValidationException::withMessages([
                'attendance' => [
                    'Anda sudah melakukan check in hari ini.'
                ]
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Distance (opsional, hanya jika assignment punya titik GPS)
        |--------------------------------------------------------------------------
        */

        $distance = null;

        $locationVerified = false;

        if ($assignment->latitude && $assignment->longitude && $assignment->radius) {

            $distance = $this->calculateDistance(

                $data['latitude'],

                $data['longitude'],

                $assignment->latitude,

                $assignment->longitude

            );

            $locationVerified = $distance <= $assignment->radius;

            if (!$locationVerified) {

                throw ValidationException::withMessages([
                    'location' => [
                        'Anda berada di luar radius lokasi assignment.'
                    ]
                ]);

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Late Detection
        |--------------------------------------------------------------------------
        */

        $late = $this->calculateLate(

            $shift->start_time,

            $shift->late_tolerance

        );

        /*
        |--------------------------------------------------------------------------
        | Save Attendance
        |--------------------------------------------------------------------------
        */

        return DB::transaction(function () use (
            $employee,
            $assignment,
            $assignmentEmployee,
            $shift,
            $user,
            $data,
            $late,
            $distance,
            $locationVerified
        ) {

            $attendance = Attendance::create([

                'company_id' => $employee->company_id,

                'employee_id' => $employee->id,

                'office_id' => null,

                'assignment_id' => $assignment->id,

                'shift_id' => $shift->id,

                'attendance_type' => 'ASSIGNMENT',

                'attendance_date' => today(),

                'check_in_time' => now(),

                'check_in_latitude' => $data['latitude'],

                'check_in_longitude' => $data['longitude'],

                'check_in_distance' => $distance,

                'allowed_radius' => $assignment->radius,

                'location_verified' => $locationVerified,

                'attendance_status' => $late['status'],

                'late_minutes' => $late['late_minutes'],

                'is_checked_in' => true,

                'is_checked_out' => false,

                'notes' => $data['notes'] ?? null,

            ]);

            /*
            |--------------------------------------------------------------------------
            | Assigned -> Accepted -> Check In -> In Progress
            |--------------------------------------------------------------------------
            | Update status assignment_employees supaya Dashboard Admin tidak
            | menampilkan status 'Assigned' padahal employee sudah check in.
            */

            if ($assignmentEmployee->status !== 'In Progress') {

                $assignmentEmployee->update([

                    'status' => 'In Progress',

                    'started_at' => now(),

                ]);

            }

            $this->addAssignmentLog(
                $assignment,
                $employee,
                $user,
                'CHECK_IN',
                'Karyawan check in pada assignment.'
            );

            return $attendance;

        });
    }

    /**
     * Get today's attendance.
     */
    public function today(User $user): ?Attendance
    {
        $employee = $user->employee;

        if (!$employee) {
            return null;
        }

        return Attendance::query()

            ->forCurrentCompany()

            ->with([
                'employee',
                'office',
                'shift',
                'assignment'
            ])
            ->where('employee_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Smart Check Out
    |--------------------------------------------------------------------------
    |
    | Dispatcher: cek attendance_type dari attendance hari ini, lalu
    | arahkan ke checkOut() (office) atau checkOutAssignment().
    |
    */

    public function smartCheckOut(
        User $user,
        array $data
    ): Attendance {

        $employee = $user->employee;

        if (!$employee) {

            throw ValidationException::withMessages([
                'employee' => [
                    'Data karyawan tidak ditemukan.'
                ]
            ]);

        }

        $attendance = Attendance::query()

            ->forCurrentCompany()

            ->where('employee_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->first();

        if (!$attendance) {

            throw ValidationException::withMessages([
                'attendance' => [
                    'Anda belum melakukan Check In.'
                ]
            ]);

        }

        if ($attendance->attendance_type === 'ASSIGNMENT') {

            return $this->checkOutAssignment($user, $data);

        }

        return $this->checkOut($user, $data);
    }

    /**
     * Employee Check Out (Office).
     */
    public function checkOut(
        User $user,
        array $data
    ): Attendance {

        $employee = $user->employee;

        if (!$employee) {

            throw ValidationException::withMessages([
                'employee' => [
                    'Data karyawan tidak ditemukan.'
                ]
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Cari attendance hari ini
        |--------------------------------------------------------------------------
        */

        $attendance = Attendance::query()

            ->forCurrentCompany()

            ->where('employee_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->first();

        if (!$attendance) {

            throw ValidationException::withMessages([
                'attendance' => [
                    'Anda belum melakukan Check In.'
                ]
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Sudah Check Out?
        |--------------------------------------------------------------------------
        */

        if ($attendance->is_checked_out) {

            throw ValidationException::withMessages([
                'attendance' => [
                    'Anda sudah melakukan Check Out.'
                ]
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan ini memang attendance tipe OFFICE
        |--------------------------------------------------------------------------
        */

        if ($attendance->attendance_type !== 'OFFICE') {

            throw ValidationException::withMessages([
                'attendance' => [
                    'Attendance ini bukan tipe Office. Gunakan Check Out Assignment.'
                ]
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Office
        |--------------------------------------------------------------------------
        */

        $office = $attendance->office;

        if (!$office) {

            throw ValidationException::withMessages([
                'office' => [
                    'Office tidak ditemukan.'
                ]
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Distance & GPS Validation
        |--------------------------------------------------------------------------
        */

        $distance = $this->calculateDistance(

            $data['latitude'],

            $data['longitude'],

            $office->latitude,

            $office->longitude

        );

        $verified = $distance <= $office->radius;

        if (!$verified) {

            throw ValidationException::withMessages([
                'location' => [
                    'Anda berada di luar radius kantor.'
                ]
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Save Check Out
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $attendance,
            $data,
            $distance,
            $verified
        ) {

            $attendance->update([

                'check_out_time' => now(),

                'check_out_latitude' => $data['latitude'],

                'check_out_longitude' => $data['longitude'],

                'check_out_distance' => $distance,

                'location_verified' => $verified,

                'is_checked_out' => true,

                'notes' => $data['notes'] ?? $attendance->notes,

            ]);

        });

        return $attendance->fresh([
            'employee',
            'office',
            'assignment',
            'shift',
        ]);
    }

    /**
     * Employee Check Out (Assignment).
     *
     * GPS hanya divalidasi kalau assignment punya titik koordinat.
     */
    public function checkOutAssignment(
        User $user,
        array $data
    ): Attendance {

        $employee = $user->employee;

        if (!$employee) {

            throw ValidationException::withMessages([
                'employee' => [
                    'Data karyawan tidak ditemukan.'
                ]
            ]);

        }

        $attendance = Attendance::query()

            ->forCurrentCompany()

            ->where('employee_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->first();

        if (!$attendance) {

            throw ValidationException::withMessages([
                'attendance' => [
                    'Anda belum melakukan Check In.'
                ]
            ]);

        }

        if ($attendance->is_checked_out) {

            throw ValidationException::withMessages([
                'attendance' => [
                    'Anda sudah melakukan Check Out.'
                ]
            ]);

        }

        if ($attendance->attendance_type !== 'ASSIGNMENT') {

            throw ValidationException::withMessages([
                'attendance' => [
                    'Attendance ini bukan tipe Assignment. Gunakan Check Out biasa.'
                ]
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Assignment
        |--------------------------------------------------------------------------
        */

        $assignment = $attendance->assignment;

        $distance = null;

        $verified = false;

        if ($assignment && $assignment->latitude && $assignment->longitude && $assignment->radius) {

            $distance = $this->calculateDistance(

                $data['latitude'],

                $data['longitude'],

                $assignment->latitude,

                $assignment->longitude

            );

            $verified = $distance <= $assignment->radius;

            if (!$verified) {

                throw ValidationException::withMessages([
                    'location' => [
                        'Anda berada di luar radius lokasi assignment.'
                    ]
                ]);

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Save Check Out
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $attendance,
            $assignment,
            $employee,
            $user,
            $data,
            $distance,
            $verified
        ) {

            $attendance->update([

                'check_out_time' => now(),

                'check_out_latitude' => $data['latitude'],

                'check_out_longitude' => $data['longitude'],

                'check_out_distance' => $distance,

                'location_verified' => $verified,

                'is_checked_out' => true,

                'notes' => $data['notes'] ?? $attendance->notes,

            ]);

            if ($assignment) {

                $this->addAssignmentLog(
                    $assignment,
                    $employee,
                    $user,
                    'CHECK_OUT',
                    'Karyawan check out pada assignment.'
                );

                /*
                |--------------------------------------------------------------------------
                | In Progress -> Completed
                |--------------------------------------------------------------------------
                | Assigned -> Accepted -> Check In -> In Progress -> Check Out -> Completed
                |
                | Ambil pivot berdasarkan assignment_id dari attendance, bukan
                | currentAssignment(), supaya tetap akurat walaupun employee
                | sudah punya assignment lain yang aktif di hari yang sama.
                */

                $assignmentEmployee = AssignmentEmployee::query()

                    ->forCurrentCompany()

                    ->where(
                        'assignment_id',
                        $assignment->id
                    )
                    ->where(
                        'employee_id',
                        $employee->id
                    )
                    ->first();

                if ($assignmentEmployee) {

                    $assignmentEmployee->update([

                        'status' => 'Completed',

                        'finished_at' => now(),

                    ]);

                }

                $this->addAssignmentLog(
                    $assignment,
                    $employee,
                    $user,
                    'ASSIGNMENT_COMPLETED',
                    'Employee completed assignment.'
                );

                /*
                |--------------------------------------------------------------------------
                | Auto-complete parent Assignment
                |--------------------------------------------------------------------------
                | Kalau semua employee yang ditugaskan pada assignment ini
                | sudah 'Completed', assignment induk ikut ditandai selesai
                | supaya dashboard admin otomatis update.
                */

                $allEmployeesCompleted = $assignment
                    ->assignmentEmployees()
                    ->where('status', '!=', 'Completed')
                    ->doesntExist();

                if ($allEmployeesCompleted && $assignment->status !== 'Completed') {

                    $assignment->update([
                        'status' => 'Completed',
                    ]);

                }

            }

        });

        return $attendance->fresh([
            'employee',
            'office',
            'assignment',
            'shift',
        ]);
    }

    /**
     * Attendance history.
     */
    public function history(User $user)
    {
        return Attendance::query()

            ->forCurrentCompany()

            ->with([
                'office',
                'shift',
                'assignment'
            ])
            ->where('employee_id', $user->employee->id)
            ->orderByDesc('attendance_date')
            ->orderByDesc('check_in_time')
            ->paginate(15);
    }

    /**
     * Attendance statistics.
     */
    public function statistics(User $user): array
    {
        $employee = $user->employee;

        $todayAttendance = Attendance::query()

            ->forCurrentCompany()

            ->where('employee_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->first();

        $monthAttendances = Attendance::query()

            ->forCurrentCompany()

            ->where('employee_id', $employee->id)
            ->whereMonth('attendance_date', now()->month)
            ->whereYear('attendance_date', now()->year);

        $present = (clone $monthAttendances)
            ->where('attendance_status', 'Present')
            ->count();

        $late = (clone $monthAttendances)
            ->where('attendance_status', 'Late')
            ->count();

        $leave = (clone $monthAttendances)
            ->where('attendance_status', 'Leave')
            ->count();

        $permission = (clone $monthAttendances)
            ->where('attendance_status', 'Permission')
            ->count();

        $absent = (clone $monthAttendances)
            ->where('attendance_status', 'Absent')
            ->count();

        $total = $monthAttendances->count();

        return [
            'today' => $todayAttendance,

            'summary' => [

                'present' => $present,
                'late' => $late,
                'leave' => $leave,
                'permission' => $permission,
                'absent' => $absent,
                'total' => $total,

            ],

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Assignment Log
    |--------------------------------------------------------------------------
    */

    /**
     * Record an assignment log entry.
     */
    protected function addAssignmentLog(
        Assignment $assignment,
        Employee $employee,
        User $user,
        string $action,
        string $description
    ): AssignmentLog {

        return AssignmentLog::create([

            'company_id' => $employee->company_id,

            'assignment_id' => $assignment->id,

            'employee_id' => $employee->id,

            'user_id' => $user->id,

            'action' => $action,

            'description' => $description,

        ]);
    }

}