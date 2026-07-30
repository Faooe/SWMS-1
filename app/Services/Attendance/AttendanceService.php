<?php

namespace App\Services\Attendance;

use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Office;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AttendanceService
{
    /*
    |--------------------------------------------------------------------------
    | Office Hours (Kondisi Kantor Normal / Tanpa Assignment)
    |--------------------------------------------------------------------------
    */

    private const OFFICE_START_TIME = '08:00:00';

    private const OFFICE_TOLERANCE_MINUTES = 15;

    private const OFFICE_END_TIME = '17:00:00';

    /*
    |--------------------------------------------------------------------------
    | Batas Check-out / Leave (berlaku untuk Office maupun Assignment)
    |--------------------------------------------------------------------------
    */

    private const CHECK_OUT_DEADLINE = '23:00:00';

    public function __construct(
        protected AttendanceLocationService $locationService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Employee's Office
    |--------------------------------------------------------------------------
    */

    public function getOffice(Employee $employee): ?Office
    {

        return $employee->currentEmployment?->office;

    }

    /*
    |--------------------------------------------------------------------------
    | Today's Office Attendance
    |--------------------------------------------------------------------------
    */

    public function getTodayOfficeAttendance(
        Employee $employee
    ): ?Attendance {

        return Attendance::query()

            ->where('employee_id', $employee->id)

            ->office()

            ->today()

            ->first();

    }

    /*
    |--------------------------------------------------------------------------
    | Today's Assignment (Read Only, untuk ditampilkan di dashboard)
    |--------------------------------------------------------------------------
    */

    public function getTodayAssignment(
    Employee $employee
    ): ?Assignment {

        return Assignment::query()

            ->forCurrentCompany()

            ->whereHas('employees', function ($query) use ($employee) {

                $query

                    ->where('employees.id', $employee->id)

                    ->whereIn('assignment_employees.status', [

                        'Assigned',

                        'Accepted',

                        'In Progress',

                    ]);

            })

            ->whereDate('start_datetime', '<=', today())

            ->whereDate('end_datetime', '>=', today())

            ->whereIn('status', [

                'Assigned',

                'In Progress',

            ])

            ->orderBy('start_datetime')

            ->first();

    }

    /*
    |--------------------------------------------------------------------------
    | Today's Assignment Attendance (Read Only)
    |--------------------------------------------------------------------------
    */

    public function getTodayAssignmentAttendance(
        Employee $employee,
        Assignment $assignment
    ): ?Attendance {

        return Attendance::query()

            ->where('employee_id', $employee->id)

            ->where('assignment_id', $assignment->id)

            ->assignment()

            ->today()

            ->first();

    }

    /*
    |--------------------------------------------------------------------------
    | Today's Attendance (Office ATAU Assignment, dipakai untuk mencegah
    | double check-in lintas tipe)
    |--------------------------------------------------------------------------
    */

    public function getTodayAnyAttendance(
        Employee $employee
    ): ?Attendance {

        return Attendance::query()

            ->where('employee_id', $employee->id)

            ->today()

            ->whereIn('attendance_type', ['OFFICE', 'ASSIGNMENT'])

            ->first();

    }

    /*
    |--------------------------------------------------------------------------
    | Check In (Office)
    |--------------------------------------------------------------------------
    */

    public function checkInOffice(
        Employee $employee,
        float $latitude,
        float $longitude
    ): array {

        $office = $this->getOffice($employee);

        if (!$office) {

            return [

                'success' => false,

                'message' => 'Kamu belum ditempatkan di office manapun.',

            ];

        }

        $existingAny = $this->getTodayAnyAttendance($employee);

        if ($existingAny && $existingAny->hasCheckedIn()) {

            if ($existingAny->attendance_type === 'ASSIGNMENT') {

                return [

                    'success' => false,

                    'message' => 'Kamu sudah check in hari ini melalui Assignment. Absensi hanya bisa dilakukan sekali dalam sehari.',

                ];

            }

            return [

                'success' => false,

                'message' => 'Kamu sudah check in hari ini.',

            ];

        }

        $location = $this->locationService->validateOffice(

            $office,

            $latitude,

            $longitude

        );

        if (!$location['allowed']) {

            return [

                'success' => false,

                'message' => 'You are outside the office area.',

                'distance' => $location['distance'],

                'radius' => $location['radius'],

            ];

        }

        $now = now();

        if ($now->format('H:i:s') > self::OFFICE_END_TIME) {

            return [

                'success' => false,

                'message' => 'Jam kerja sudah berakhir. Silakan hubungi admin/HR.',

            ];

        }

        [$status, $lateMinutes] = $this->resolveAttendanceStatus(

            self::OFFICE_START_TIME,

            self::OFFICE_TOLERANCE_MINUTES,

            self::OFFICE_END_TIME

        );

        $attendance = Attendance::updateOrCreate(

            [

                'employee_id' => $employee->id,

                'office_id' => $office->id,

                'attendance_type' => 'OFFICE',

                'attendance_date' => today()->toDateString(),

            ],

            [

                'company_id' => $employee->company_id,

                'check_in_time' => now()->format('H:i:s'),

                'check_in_latitude' => $latitude,

                'check_in_longitude' => $longitude,

                'check_in_distance' => $location['distance'],

                'allowed_radius' => $location['radius'],

                'location_verified' => true,

                'attendance_status' => $status,

                'is_checked_in' => true,

                'late_minutes' => $lateMinutes,

            ]

        );

        return [

            'success' => true,

            'message' => 'Check in berhasil.',

            'attendance' => $attendance,

        ];

    }

    /*
    |--------------------------------------------------------------------------
    | Check Out (Office)
    |--------------------------------------------------------------------------
    */

    public function checkOutOffice(
        Employee $employee,
        float $latitude,
        float $longitude
    ): array {

        $office = $this->getOffice($employee);

        if (!$office) {

            return [

                'success' => false,

                'message' => 'Kamu belum ditempatkan di office manapun.',

            ];

        }

        $attendance = $this->getTodayOfficeAttendance($employee);

        if (!$attendance || !$attendance->canCheckOut()) {

            return [

                'success' => false,

                'message' => $attendance?->hasCheckedOut()

                    ? 'Kamu sudah check out hari ini.'

                    : 'Kamu belum check in hari ini.',

            ];

        }

        if (now()->format('H:i:s') > self::CHECK_OUT_DEADLINE) {

            return [

                'success' => false,

                'message' => 'Batas waktu check out (23:00) sudah terlewat.',

            ];

        }

        $location = $this->locationService->validateOffice(

            $office,

            $latitude,

            $longitude

        );

        if (!$location['allowed']) {

            return [

                'success' => false,

                'message' => 'You are outside the office area.',

                'distance' => $location['distance'],

                'radius' => $location['radius'],

            ];

        }

        $attendance->update([

            'check_out_time' => now()->format('H:i:s'),

            'check_out_latitude' => $latitude,

            'check_out_longitude' => $longitude,

            'check_out_distance' => $location['distance'],

            'is_checked_out' => true,

        ]);

        return [

            'success' => true,

            'message' => 'Check out berhasil.',

            'attendance' => $attendance,

        ];

    }
    /*
    |--------------------------------------------------------------------------
    | Check In (Assignment)
    |--------------------------------------------------------------------------
    */

    public function checkInAssignment(
        Employee $employee,
        Assignment $assignment,
        float $latitude,
        float $longitude
    ): array {

        if (!$this->isWithinAssignmentPeriod($assignment)) {

            return [

                'success' => false,

                'message' => 'Hari ini berada di luar periode assignment.',

            ];

        }

        $existing = $this->getTodayAssignmentAttendance(
            $employee,
            $assignment
        );

        if ($existing && $existing->hasCheckedIn()) {

            return [

                'success' => false,

                'message' => 'Kamu sudah check in untuk assignment ini hari ini.',

            ];

        }

        $existingOffice = $this->getTodayOfficeAttendance($employee);

        if ($existingOffice && $existingOffice->hasCheckedIn()) {

            return [

                'success' => false,

                'message' => 'Kamu sudah check in hari ini melalui Office. Absensi hanya bisa dilakukan sekali dalam sehari.',

            ];

        }

        if (now()->format('H:i:s') > $assignment->end_datetime->format('H:i:s')) {

            return [

                'success' => false,

                'message' => 'Jam kerja assignment hari ini sudah berakhir.',

            ];

        }

        $location = $this->locationService->validateAssignment(

            $assignment,

            $latitude,

            $longitude

        );

        if (!$location['allowed']) {

            return [

                'success' => false,

                'message' => 'You are outside the assignment area.',

                'distance' => $location['distance'],

                'radius' => $location['radius'],

            ];

        }

        [$status, $lateMinutes] = $this->resolveAttendanceStatus(

            $assignment->start_datetime->format('H:i:s'),

            self::OFFICE_TOLERANCE_MINUTES,

            $assignment->end_datetime->format('H:i:s')

        );

        $attendance = Attendance::updateOrCreate(

            [

                'employee_id' => $employee->id,

                'assignment_id' => $assignment->id,

                'attendance_type' => 'ASSIGNMENT',

                'attendance_date' => today()->toDateString(),

            ],

            [

                'company_id' => $employee->company_id,

                'office_id' => $assignment->office_id,

                'check_in_time' => now()->format('H:i:s'),

                'check_in_latitude' => $latitude,

                'check_in_longitude' => $longitude,

                'check_in_distance' => $location['distance'],

                'allowed_radius' => $location['radius'],

                'location_verified' => true,

                'attendance_status' => $status,

                'is_checked_in' => true,

                'late_minutes' => $lateMinutes,

            ]

        );

        return [

            'success' => true,

            'message' => 'Check in assignment berhasil.',

            'attendance' => $attendance,

        ];

    }

    /*
    |--------------------------------------------------------------------------
    | Check Out (Assignment)
    |--------------------------------------------------------------------------
    */

    public function checkOutAssignment(
        Employee $employee,
        Assignment $assignment,
        float $latitude,
        float $longitude
    ): array {

        $attendance = $this->getTodayAssignmentAttendance(
            $employee,
            $assignment
        );

        if (!$attendance || !$attendance->canCheckOut()) {

            return [

                'success' => false,

                'message' => $attendance?->hasCheckedOut()

                    ? 'Kamu sudah check out untuk assignment ini.'

                    : 'Kamu belum check in untuk assignment ini.',

            ];

        }

        if (now()->format('H:i:s') > self::CHECK_OUT_DEADLINE) {

            return [

                'success' => false,

                'message' => 'Batas waktu check out (23:00) sudah terlewat.',

            ];

        }

        $location = $this->locationService->validateAssignment(

            $assignment,

            $latitude,

            $longitude

        );

        if (!$location['allowed']) {

            return [

                'success' => false,

                'message' => 'You are outside the assignment area.',

                'distance' => $location['distance'],

                'radius' => $location['radius'],

            ];

        }

        $attendance->update([

            'check_out_time' => now()->format('H:i:s'),

            'check_out_latitude' => $latitude,

            'check_out_longitude' => $longitude,

            'check_out_distance' => $location['distance'],

            'is_checked_out' => true,

        ]);

        return [

            'success' => true,

            'message' => 'Check out assignment berhasil.',

            'attendance' => $attendance,

        ];

    }

    /*
    |--------------------------------------------------------------------------
    | Resolve Attendance Status (Present / Late)
    |--------------------------------------------------------------------------
    */

    private function resolveAttendanceStatus(
    string $startTime,
    int $toleranceMinutes,
    string $endTime
    ): array {

        $start = Carbon::createFromFormat('H:i:s', $startTime);

        $deadline = $start->copy()->addMinutes($toleranceMinutes);

        $nowTime = Carbon::createFromFormat(

            'H:i:s',

            now()->format('H:i:s')

        );

        if ($nowTime->greaterThan($deadline)) {

            // Carbon 3's diffInMinutes() returns a signed float by
            // default (unlike Carbon 2, which returned an absolute
            // int). "late_minutes" is an integer column, so we must
            // force it back to an absolute, rounded whole number or
            // Postgres rejects the insert with a 22P02 error.
            $lateMinutes = (int) round(
                abs($nowTime->diffInMinutes($start))
            );

            return ['Late', $lateMinutes];

        }

        return ['Present', 0];

    }

    /*
    |--------------------------------------------------------------------------
    | Cek Apakah Hari Ini Masih Dalam Periode Assignment
    |--------------------------------------------------------------------------
    */

    private function isWithinAssignmentPeriod(Assignment $assignment): bool
    {

        $today = today();

        $startDate = $assignment->start_datetime->copy()->startOfDay();

        $endDate = $assignment->end_datetime->copy()->startOfDay();

        return $today->between($startDate, $endDate, true);

    }

    /*
    |--------------------------------------------------------------------------
    | History
    |--------------------------------------------------------------------------
    */

    public function getHistory(
        Employee $employee,
        array $filters = []
    ): LengthAwarePaginator {

        $query = Attendance::query()

            ->where('employee_id', $employee->id)

            ->with(['office', 'assignment', 'shift']);

        if (!empty($filters['month'])) {

            $date = Carbon::parse($filters['month']);

            $query

                ->whereMonth('attendance_date', $date->month)

                ->whereYear('attendance_date', $date->year);

        }

        if (!empty($filters['status'])) {

            $query->where(
                'attendance_status',
                $filters['status']
            );

        }

        if (!empty($filters['type'])) {

            $query->where(
                'attendance_type',
                $filters['type']
            );

        }

        return $query

            ->orderByDesc('attendance_date')

            ->paginate(
                $filters['per_page'] ?? 15
            );

    }

    /*
    |--------------------------------------------------------------------------
    | Monthly Summary
    |--------------------------------------------------------------------------
    */

    public function getMonthlySummary(
        Employee $employee,
        ?string $month = null
    ): array {

        $date = $month ? Carbon::parse($month) : now();

        $base = Attendance::query()

            ->where('employee_id', $employee->id)

            ->whereMonth('attendance_date', $date->month)

            ->whereYear('attendance_date', $date->year);

        return [

            'present' => (clone $base)

                ->where('attendance_status', 'Present')

                ->count(),

            'late' => (clone $base)

                ->where('attendance_status', 'Late')

                ->count(),

            'leave' => (clone $base)

                ->where('attendance_status', 'Leave')

                ->count(),

            'permission' => (clone $base)

                ->where('attendance_status', 'Permission')

                ->count(),

            'absent' => (clone $base)

                ->where('attendance_status', 'Absent')

                ->count(),

        ];

    }
}