<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\AssignmentEmployee;
use App\Models\AssignmentLog;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use App\Services\Attendance\AttendanceLocationService;
use App\Services\Attendance\AttendanceStatusSummary;
use App\Services\Attendance\AttendanceTimeCalculator;
use App\Services\Attendance\HaversineService;
use App\Support\Pagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AttendanceService extends BaseService
{
    public function __construct(
        private readonly AttendanceTimeCalculator $timeCalculator,
        private readonly HaversineService $haversineService,
        private readonly AttendanceStatusSummary $statusSummary,
    ) {}

    private function requireEmployee(User $user): Employee
    {
        $employee = $user->employee;

        if (! $employee) {
            throw ValidationException::withMessages([
                'employee' => ['Data karyawan tidak ditemukan.'],
            ]);
        }

        return $employee;
    }

    /*
    |--------------------------------------------------------------------------
    | Default Office Hours (dipakai saat employment tidak punya shift --
    | fitur Shift sudah dilepas dari form Add/Edit Employee. Nilainya
    | disamakan dengan Services\Attendance\AttendanceService yang dipakai
    | browser, supaya perilaku web & mobile konsisten.)
    |--------------------------------------------------------------------------
    */

    private const OFFICE_START_TIME = '08:00:00';

    private const OFFICE_TOLERANCE_MINUTES = 15;

    private const OFFICE_END_TIME = '17:00:00';

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
        return $this->haversineService->distance($lat1, $lon1, $lat2, $lon2);
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

        return $this->haversineService->isWithinRadius($distance, $radius);
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
        return $this->timeCalculator->checkInStatus($shiftStart, $tolerance);
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

        $employee = $this->requireEmployee($user);

        // Phase 3 memisahkan Attendance Office dari Daily Assignment. Jika
        // employee punya office, tombol Attendance umum SELALU mengelola
        // record OFFICE; check-in assignment dilakukan dari My Assignment.
        if ($employee->currentEmployment?->office) {
            return $this->checkIn($user, $data);
        }

        // Fallback untuk field-worker yang memang tidak punya Office. Jangan
        // pernah bypass Accept atau jadwal assignment.
        $currentAssignment = $employee->currentAssignment;
        $assignment = $currentAssignment?->assignment;

        if (
            $currentAssignment
            && $assignment
            && in_array($currentAssignment->status, AssignmentEmployee::workingStatuses(), true)
            && in_array($assignment->status, [Assignment::STATUS_ASSIGNED, Assignment::STATUS_IN_PROGRESS], true)
            && today()->betweenIncluded(
                $assignment->start_datetime->copy()->startOfDay(),
                $assignment->end_datetime->copy()->startOfDay()
            )
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

        $employee = $this->requireEmployee($user);

        /*
        |--------------------------------------------------------------------------
        | Employment
        |--------------------------------------------------------------------------
        */

        $employment = $employee->currentEmployment;

        if (! $employment) {

            throw ValidationException::withMessages([
                'employment' => [
                    'Penempatan kerja belum tersedia.',
                ],
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Office
        |--------------------------------------------------------------------------
        */

        $office = $employment->office;

        if (! $office) {

            throw ValidationException::withMessages([
                'office' => [
                    'Office tidak ditemukan.',
                ],
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Shift (opsional -- fitur Shift sudah dilepas dari form Add/Edit
        | Employee, jadi employment boleh tidak punya shift. Kalau kosong,
        | pakai jam kantor default, sama seperti Services\Attendance\AttendanceService
        | (web) dan AbsentAttendanceService::isPastShiftEnd()).
        |--------------------------------------------------------------------------
        */

        $shift = $employment->shift;

        /*
        |--------------------------------------------------------------------------
        | Already Check In Today
        |--------------------------------------------------------------------------
        */

        $alreadyCheckedIn = Attendance::query()
            ->where('employee_id', $employee->id)
            ->where('attendance_type', 'OFFICE')
            ->whereDate('attendance_date', today())
            ->exists();

        if ($alreadyCheckedIn) {

            throw ValidationException::withMessages([
                'attendance' => [
                    'Anda sudah melakukan check in hari ini.',
                ],
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

        if (! $locationVerified) {

            throw ValidationException::withMessages([
                'location' => [
                    'Anda berada di luar radius kantor.',
                ],
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Late Detection
        |--------------------------------------------------------------------------
        */

        $late = $this->calculateLate(

            $shift?->start_time ?? self::OFFICE_START_TIME,

            $shift?->late_tolerance ?? self::OFFICE_TOLERANCE_MINUTES

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

                'shift_id' => $shift?->id,

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

        $employee = $this->requireEmployee($user);

        /*
        |--------------------------------------------------------------------------
        | Assignment Employee (pivot) -> Assignment
        |--------------------------------------------------------------------------
        */

        $assignmentEmployee = $employee->currentAssignment;

        if (! $assignmentEmployee || $assignmentEmployee->status === AssignmentEmployee::STATUS_COMPLETED) {

            throw ValidationException::withMessages([
                'assignment' => [
                    'Assignment aktif tidak ditemukan.',
                ],
            ]);

        }

        $assignment = $assignmentEmployee->assignment;

        if (! $assignment) {

            throw ValidationException::withMessages([
                'assignment' => [
                    'Data assignment tidak ditemukan.',
                ],
            ]);

        }

        if (! in_array($assignmentEmployee->status, AssignmentEmployee::workingStatuses(), true)
            || ! in_array($assignment->status, [Assignment::STATUS_ASSIGNED, Assignment::STATUS_IN_PROGRESS], true)
            || ! today()->betweenIncluded(
                $assignment->start_datetime->copy()->startOfDay(),
                $assignment->end_datetime->copy()->startOfDay()
            )) {
            throw ValidationException::withMessages([
                'assignment' => ['Assignment harus diterima dan sudah berada dalam periode kerja.'],
            ]);
        }

        $dailyStart = today()->setTimeFromTimeString($assignment->start_datetime->format('H:i:s'));
        if (now()->lt($dailyStart)) {
            throw ValidationException::withMessages([
                'assignment' => ['Jam check in assignment belum dimulai.'],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Employment / Shift (tetap dipakai untuk keterlambatan)
        |--------------------------------------------------------------------------
        */

        $employment = $employee->currentEmployment;

        if (! $employment) {

            throw ValidationException::withMessages([
                'employment' => [
                    'Penempatan kerja belum tersedia.',
                ],
            ]);

        }

        $shift = $employment->shift;

        /*
        |--------------------------------------------------------------------------
        | Already Check In Today
        |--------------------------------------------------------------------------
        */

        $alreadyCheckedIn = Attendance::query()
            ->where('employee_id', $employee->id)
            ->where('assignment_id', $assignment->id)
            ->where('attendance_type', 'ASSIGNMENT')
            ->whereDate('attendance_date', today())
            ->exists();

        if ($alreadyCheckedIn) {

            throw ValidationException::withMessages([
                'attendance' => [
                    'Anda sudah melakukan check in hari ini.',
                ],
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Distance (opsional, hanya jika assignment punya titik GPS)
        |--------------------------------------------------------------------------
        */

        $location = app(AttendanceLocationService::class)
            ->validateAssignment(
                $assignment,
                (float) $data['latitude'],
                (float) $data['longitude']
            );

        $distance = $location['distance'];
        $locationVerified = $location['allowed'];

        if (! $locationVerified) {
            throw ValidationException::withMessages([
                'location' => [
                    $location['method'] === 'polygon'
                        ? 'Anda berada di luar area polygon assignment.'
                        : 'Anda berada di luar radius lokasi assignment.',
                ],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Late Detection
        |--------------------------------------------------------------------------
        */

        $late = $this->calculateLate(

            $shift?->start_time ?? self::OFFICE_START_TIME,

            $shift?->late_tolerance ?? self::OFFICE_TOLERANCE_MINUTES

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

                'shift_id' => $shift?->id,

                'attendance_type' => 'ASSIGNMENT',

                'attendance_date' => today(),

                'check_in_time' => now(),

                'check_in_latitude' => $data['latitude'],

                'check_in_longitude' => $data['longitude'],

                'check_in_distance' => $distance,

                'allowed_radius' => $location['radius'],

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

            if ($assignmentEmployee->status !== AssignmentEmployee::STATUS_IN_PROGRESS) {

                $assignmentEmployee->update([

                    'status' => AssignmentEmployee::STATUS_IN_PROGRESS,

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

    /*
    |--------------------------------------------------------------------------
    | Check In Context
    |--------------------------------------------------------------------------
    |
    | Dipakai layar "Attendance" sebelum karyawan check-in -- beda dengan
    | today() yang null selama belum ada Attendance record, context ini
    | selalu mengembalikan office & assignment aktif employee supaya UI
    | (peta, radius, dsb) bisa langsung ditampilkan dari awal, sama seperti
    | EmployeeAttendanceController@index di sisi web.
    |
    */

    public function checkInContext(User $user): array
    {
        $employee = $user->employee;

        if (! $employee) {
            return [
                'office' => null,
                'assignment' => null,
            ];
        }

        $office = $employee->currentEmployment?->office;

        $assignmentEmployee = $employee->currentAssignment;

        $assignment = $assignmentEmployee?->assignment;

        return [
            'office' => $office,
            'assignment' => $assignment,
        ];
    }

    /**
     * Get today's attendance.
     */
    public function today(User $user): ?Attendance
    {
        $employee = $user->employee;

        if (! $employee) {
            return null;
        }

        return Attendance::query()

            ->forCurrentCompany()

            ->with([
                'employee',
                'office',
                'shift',
                'assignment',
            ])
            ->canonicalDaily()
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

        $employee = $this->requireEmployee($user);

        $attendance = Attendance::query()

            ->canonicalDaily()

            ->forCurrentCompany()

            ->where('employee_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->first();

        if (! $attendance) {

            throw ValidationException::withMessages([
                'attendance' => [
                    'Anda belum melakukan Check In.',
                ],
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

        $employee = $this->requireEmployee($user);

        /*
        |--------------------------------------------------------------------------
        | Cari attendance hari ini
        |--------------------------------------------------------------------------
        */

        $attendance = Attendance::query()

            ->forCurrentCompany()

            ->where('attendance_type', 'OFFICE')

            ->where('employee_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->first();

        if (! $attendance) {

            throw ValidationException::withMessages([
                'attendance' => [
                    'Anda belum melakukan Check In.',
                ],
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
                    'Anda sudah melakukan Check Out.',
                ],
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
                    'Attendance ini bukan tipe Office. Gunakan Check Out Assignment.',
                ],
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Office
        |--------------------------------------------------------------------------
        */

        $office = $attendance->office;

        if (! $office) {

            throw ValidationException::withMessages([
                'office' => [
                    'Office tidak ditemukan.',
                ],
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

        if (! $verified) {

            throw ValidationException::withMessages([
                'location' => [
                    'Anda berada di luar radius kantor.',
                ],
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Save Check Out
        |--------------------------------------------------------------------------
        */

        $metrics = $this->timeCalculator->checkoutMetrics(
            $attendance,
            $attendance->shift?->end_time ?? self::OFFICE_END_TIME
        );

        DB::transaction(function () use (
            $attendance,
            $data,
            $distance,
            $verified,
            $metrics
        ) {
            $this->applyCheckOut($attendance, $data, $distance, $verified, $metrics);
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

        $employee = $this->requireEmployee($user);

        $attendance = Attendance::query()

            ->forCurrentCompany()

            ->where('attendance_type', 'ASSIGNMENT')
            ->where('is_checked_out', false)

            ->where('employee_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->latest('id')
            ->first();

        if (! $attendance) {

            throw ValidationException::withMessages([
                'attendance' => [
                    'Anda belum melakukan Check In.',
                ],
            ]);

        }

        if ($attendance->is_checked_out) {

            throw ValidationException::withMessages([
                'attendance' => [
                    'Anda sudah melakukan Check Out.',
                ],
            ]);

        }

        if ($attendance->attendance_type !== 'ASSIGNMENT') {

            throw ValidationException::withMessages([
                'attendance' => [
                    'Attendance ini bukan tipe Assignment. Gunakan Check Out biasa.',
                ],
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Assignment
        |--------------------------------------------------------------------------
        */

        $assignment = $attendance->assignment;
        $office = $employee->currentEmployment?->office;

        $location = $assignment
            ? app(AttendanceLocationService::class)->validateAssignment(
                $assignment,
                (float) $data['latitude'],
                (float) $data['longitude']
            )
            : null;

        $distance = $location['distance'] ?? null;
        $verified = $location['allowed'] ?? false;
        $verifiedAt = $verified ? 'ASSIGNMENT' : null;

        // Attendance yang berasal dari assignment boleh diakhiri di geofence
        // assignment ATAU Office asal employee. Ini juga menjadi fallback aman
        // untuk record ASSIGNMENT lama ketika assignment-nya sudah selesai /
        // tidak lagi tersedia di context: employee tetap harus berada di Office,
        // bukan otomatis boleh Check Out dari lokasi mana saja.
        if (! $verified && $office?->latitude !== null && $office?->longitude !== null && $office?->radius !== null) {
            $officeDistance = $this->calculateDistance(
                (float) $data['latitude'],
                (float) $data['longitude'],
                (float) $office->latitude,
                (float) $office->longitude
            );

            if ($officeDistance <= (int) $office->radius) {
                $verified = true;
                $verifiedAt = 'OFFICE';
                $distance = $officeDistance;
            }
        }

        if (! $verified) {
            if ($assignment && $office) {
                $message = 'Check Out attendance harus dilakukan di area assignment atau office kamu.';
            } elseif ($assignment) {
                $message = 'Check Out attendance harus dilakukan di area assignment.';
            } elseif ($office) {
                $message = 'Assignment sudah tidak aktif. Check Out attendance harus dilakukan di area office kamu.';
            } else {
                $message = 'Lokasi Check Out attendance belum dikonfigurasi. Hubungi Company Admin.';
            }

            throw ValidationException::withMessages([
                'location' => [$message],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Save Check Out
        |--------------------------------------------------------------------------
        */

        $expectedEnd = $employee->currentEmployment?->shift?->end_time
            ?? ($employee->currentEmployment?->office ? self::OFFICE_END_TIME : optional($assignment?->end_datetime)->format('H:i:s'))
            ?? self::OFFICE_END_TIME;
        $metrics = $this->timeCalculator->checkoutMetrics($attendance, $expectedEnd);

        DB::transaction(function () use (
            $attendance,
            $assignment,
            $employee,
            $user,
            $data,
            $distance,
            $verified,
            $metrics
        ) {
            $this->applyCheckOut($attendance, $data, $distance, $verified, $metrics);

            if ($assignment) {

                $this->addAssignmentLog(
                    $assignment,
                    $employee,
                    $user,
                    'CHECK_OUT',
                    'Karyawan check out pada assignment.'
                );

                // Phase 3: Check Out hanya menyelesaikan attendance hari ini.
                // Status Assignment tetap mengikuti workflow submit evidence -> review company.
            }

        });

        return $attendance->fresh([
            'employee',
            'office',
            'assignment',
            'shift',
        ]);
    }

    private function applyCheckOut(
        Attendance $attendance,
        array $data,
        ?float $distance,
        bool $verified,
        array $metrics
    ): void {
        $attendance->update([
            'check_out_time' => now(),
            'check_out_latitude' => $data['latitude'],
            'check_out_longitude' => $data['longitude'],
            'check_out_distance' => $distance,
            'location_verified' => $verified,
            'is_checked_out' => true,
            'work_minutes' => $metrics['work_minutes'],
            'early_leave_minutes' => $metrics['early_leave_minutes'],
            'overtime_minutes' => $metrics['overtime_minutes'],
            'notes' => $data['notes'] ?? $attendance->notes,
        ]);
    }

    /**
     * Attendance history.
     */
    public function history(User $user, array $filters = [])
    {
        $query = Attendance::query()
            ->canonicalDaily()
            ->forCurrentCompany()
            ->with(['office', 'shift', 'assignment'])
            ->where('employee_id', $user->employee->id);

        if (! empty($filters['month'])) {
            $month = Carbon::parse($filters['month']);
            $query->whereMonth('attendance_date', $month->month)
                ->whereYear('attendance_date', $month->year);
        }

        if (! empty($filters['status'])) {
            $query->where('attendance_status', $filters['status']);
        }

        if (! empty($filters['type'])) {
            $query->where('attendance_type', $filters['type']);
        }

        $perPage = Pagination::normalize($filters['per_page'] ?? null, 10, 100);

        return $query
            ->orderByDesc('attendance_date')
            ->orderByDesc('check_in_time')
            ->paginate($perPage);
    }

    public function historySummary(User $user, ?string $month = null): array
    {
        $date = $month ? Carbon::parse($month) : now();

        $base = Attendance::query()
            ->canonicalDaily()
            ->forCurrentCompany()
            ->where('employee_id', $user->employee->id)
            ->whereMonth('attendance_date', $date->month)
            ->whereYear('attendance_date', $date->year);

        return [
            'month' => $date->format('Y-m'),
            ...$this->statusSummary->build($base, includeWorkMinutes: true),
        ];
    }

    /**
     * Attendance statistics.
     */
    public function statistics(User $user): array
    {
        $employee = $user->employee;

        $todayAttendance = Attendance::query()

            ->canonicalDaily()

            ->forCurrentCompany()

            ->where('employee_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->first();

        $monthAttendances = Attendance::query()

            ->canonicalDaily()

            ->forCurrentCompany()

            ->where('employee_id', $employee->id)
            ->whereMonth('attendance_date', now()->month)
            ->whereYear('attendance_date', now()->year);

        return [
            'today' => $todayAttendance,
            'summary' => $this->statusSummary->build($monthAttendances),
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

            'assignment_id' => $assignment->id,

            'employee_id' => $employee->id,

            'user_id' => $user->id,

            'action' => $action,

            'description' => $description,

        ]);
    }
}
