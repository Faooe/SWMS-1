<?php

namespace App\Services\Attendance;

use App\Models\Assignment;
use App\Models\AssignmentEmployee;
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
        protected AttendanceLocationService $locationService,
        protected WorkCalendarService $workCalendarService
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

                    ])
                    // A legacy pivot may still say Accepted/In Progress even
                    // after the deadline workflow marked it Not Worked. Such
                    // assignments must never reappear on Attendance.
                    ->where(function ($pivot) {
                        $pivot->whereNull('assignment_employees.review_status')
                            ->orWhereNotIn('assignment_employees.review_status', [
                                'Not Worked',
                                'Expired',
                            ]);
                    });

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
    | Apakah employee sudah absen hari ini? (Office ATAU Assignment manapun)
    |
    | Dipakai untuk menyembunyikan tombol "Check In Lokasi" di assignment
    | lain begitu absensi harian sudah tercatat sekali -- karena backend
    | (checkInOffice/checkInAssignment) memang membatasi absensi cuma 1x
    | per hari, jadi UI tidak perlu lagi menampilkan aksi yang pasti gagal.
    |--------------------------------------------------------------------------
    */

    public function hasAttendanceToday(
        Employee $employee
    ): bool {

        return $this->getTodayAnyAttendance($employee) !== null;

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

        $metrics = $this->checkoutMetrics(
            $attendance,
            $attendance->shift?->end_time ?? self::OFFICE_END_TIME
        );

        $attendance->update([

            'check_out_time' => now()->format('H:i:s'),

            'check_out_latitude' => $latitude,

            'check_out_longitude' => $longitude,

            'check_out_distance' => $location['distance'],

            'is_checked_out' => true,

            'work_minutes' => $metrics['work_minutes'],
            'early_leave_minutes' => $metrics['early_leave_minutes'],
            'overtime_minutes' => $metrics['overtime_minutes'],

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

        $assignmentEmployee = AssignmentEmployee::query()
            ->where('assignment_id', $assignment->id)
            ->where('employee_id', $employee->id)
            ->first();

        if (!$assignmentEmployee
            || in_array($assignmentEmployee->review_status, ['Not Worked', 'Expired'], true)
            || !in_array($assignmentEmployee->status, ['Accepted', 'In Progress'], true)
            || ($assignment->end_datetime
                && now()->isAfter($assignment->end_datetime)
                && !($assignment->daily_attendance_enabled && today()->isSameDay($assignment->end_datetime)))) {
            return [
                'success' => false,
                'message' => 'Assignment sudah berakhir atau berstatus Tidak Dikerjakan. Attendance assignment tidak tersedia.',
            ];
        }

        if (!$this->isWithinAssignmentPeriod($assignment)) {

            return [

                'success' => false,

                'message' => 'Hari ini berada di luar periode assignment.',

            ];

        }

        if ($assignment->daily_attendance_enabled
            && $assignment->attendance_day_rule === 'WORK_CALENDAR'
            && !$this->workCalendarService->isWorkingDay($employee->company, today())) {
            return [
                'success' => false,
                'message' => 'Hari ini bukan hari kerja efektif untuk assignment ini.',
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

        // Daily Attendance adalah absensi khusus assignment per tanggal.
        // Jika employee sudah melakukan Attendance Office, ia tetap harus
        // dapat Check In ke assignment ini agar kalender assignment, status
        // In Progress, dan persentase kehadirannya tercatat dengan benar.
        // Rule satu-absensi-per-hari lama tetap dipertahankan untuk
        // assignment non-Daily Attendance.
        if (!$assignment->daily_attendance_enabled
            && $existingOffice
            && $existingOffice->hasCheckedIn()) {

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

        $assignmentEmployee = AssignmentEmployee::query()
            ->where('assignment_id', $assignment->id)
            ->where('employee_id', $employee->id)
            ->first();

        // Untuk assignment Daily Attendance, hari terakhir punya grace period
        // khusus CHECK OUT sampai 23:00. Deadline normal end_datetime tetap
        // berlaku untuk Accept/Reject/Check In, tetapi attendance yang sudah
        // dimulai harus tetap bisa ditutup pada hari terakhir.
        $checkoutDeadline = $assignment->end_datetime?->copy();
        if ($checkoutDeadline && $assignment->daily_attendance_enabled) {
            $checkoutDeadline->setTime(23, 0, 0);
        }

        if (!$assignmentEmployee
            || in_array($assignmentEmployee->review_status, ['Not Worked', 'Expired'], true)
            || ($checkoutDeadline && now()->greaterThan($checkoutDeadline))) {
            return [
                'success' => false,
                'message' => 'Assignment sudah berakhir atau berstatus Tidak Dikerjakan. Check out assignment tidak dapat dilakukan.',
            ];
        }

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

        /*
        |--------------------------------------------------------------------------
        | Check-out assignment WAJIB dilakukan SETELAH foto bukti kerja +
        | catatan pengerjaan disubmit (assignment_employees.completion_photo
        | terisi). Tanpa guard ini, employee bisa check-out (attendance
        | selesai) tanpa pernah mengirim laporan hasil kerja sama sekali --
        | assignment-nya nyangkut selamanya di 'In Progress' tanpa ada bukti
        | apa pun buat direview company. Employee TETAP boleh menyelesaikan
        | absensi hari itu (attendance Office biasa / assignment lain) --
        | yang diblokir cuma check-out UNTUK ASSIGNMENT INI.
        |--------------------------------------------------------------------------
        */

        $assignmentEmployee = AssignmentEmployee::query()

            ->where('assignment_id', $assignment->id)

            ->where('employee_id', $employee->id)

            ->first();

        if (!$assignment->daily_attendance_enabled
            && (!$assignmentEmployee || !$assignmentEmployee->completion_photo)) {

            return [

                'success' => false,

                'message' => 'Upload dulu foto bukti & catatan hasil kerja sebelum check out.',

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

        $metrics = $this->checkoutMetrics(
            $attendance,
            $assignment->end_datetime->format('H:i:s')
        );

        $attendance->update([

            'check_out_time' => now()->format('H:i:s'),

            'check_out_latitude' => $latitude,

            'check_out_longitude' => $longitude,

            'check_out_distance' => $location['distance'],

            'is_checked_out' => true,

            'work_minutes' => $metrics['work_minutes'],
            'early_leave_minutes' => $metrics['early_leave_minutes'],
            'overtime_minutes' => $metrics['overtime_minutes'],

        ]);

        return [

            'success' => true,

            'message' => 'Check out assignment berhasil.',

            'attendance' => $attendance,

        ];

    }

    private function checkoutMetrics(Attendance $attendance, string $expectedEndTime): array
    {
        $date = $attendance->attendance_date?->toDateString() ?? today()->toDateString();
        $checkInRaw = $attendance->getRawOriginal('check_in_time') ?: optional($attendance->check_in_time)->format('H:i:s');
        $checkIn = Carbon::parse($date . ' ' . $checkInRaw);
        $checkOut = now();
        $expectedEnd = Carbon::parse($date . ' ' . $expectedEndTime);

        return [
            'work_minutes' => max(0, (int) round($checkIn->diffInMinutes($checkOut))),
            'early_leave_minutes' => $checkOut->lt($expectedEnd)
                ? max(0, (int) round($checkOut->diffInMinutes($expectedEnd))) : 0,
            'overtime_minutes' => $checkOut->gt($expectedEnd)
                ? max(0, (int) round($expectedEnd->diffInMinutes($checkOut))) : 0,
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