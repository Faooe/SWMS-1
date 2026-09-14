<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\AssignmentEmployee;
use App\Models\AssignmentLog;
use App\Models\Attendance;
use App\Models\User;
use App\Notifications\AssignmentCompletionSubmitted;
use App\Notifications\AssignmentResponseUpdated;
use App\Services\Attendance\AttendanceLocationService;
use App\Services\Attendance\AttendanceService;
use App\Services\Attendance\WorkCalendarService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class EmployeeAssignmentService
{
    public function __construct(
        protected AttendanceService $attendanceService,
        protected EmployeeAssignmentQuery $assignmentQuery,
        protected EmployeeAssignmentDeadlineSynchronizer $deadlineSynchronizer,
        protected EmployeeAssignmentStatistics $assignmentStatistics,
    ) {}

    /*
    |--------------------------------------------------------------------------
    | My Assignment List
    |--------------------------------------------------------------------------
    */

    public function getAssignments(
        User $user,
        array $filters = []
    ): LengthAwarePaginator {
        $employee = $user->employee;
        $this->deadlineSynchronizer->sync($user);

        return $this->assignmentQuery->paginate($employee->id, $filters);
    }

    /*
    |--------------------------------------------------------------------------
    | Assignment Detail
    |--------------------------------------------------------------------------
    */

    public function find(
        User $user,
        string $uuid
    ): Assignment {

        $employee = $user->employee;
        $this->deadlineSynchronizer->sync($user);

        return Assignment::query()

            ->with([

                'office',

                'creator.employee',

                'employees.currentEmployment.position',

                'employees.currentEmployment.office',

                'logs.user.employee',

                'logs.employee',

                'attachments',

            ])

            ->where(

                'uuid',

                $uuid

            )

            ->whereHas(

                'employees',

                function ($query) use ($employee) {

                    $query->where(

                        'employees.id',

                        $employee->id

                    );

                }

            )
            ->where('assignments.status', '!=', 'Draft')

            ->firstOrFail();

    }
    /*
    |--------------------------------------------------------------------------
    | Accept Assignment
    |--------------------------------------------------------------------------
    */

    public function accept(
        User $user,
        string $uuid
    ): Assignment {

        $employee = $user->employee;

        /*
        |--------------------------------------------------------------------------
        | Assignment
        |--------------------------------------------------------------------------
        */

        $assignment = $this->find(
            $user,
            $uuid
        );

        /*
        |--------------------------------------------------------------------------
        | Pivot
        |--------------------------------------------------------------------------
        */

        $assignmentEmployee = AssignmentEmployee::query()

            ->where('assignment_id', $assignment->id)

            ->where('employee_id', $employee->id)

            ->firstOrFail();

        if (
            in_array($assignmentEmployee->review_status, ['Not Worked', 'Expired'], true)
            || ($assignment->end_datetime && now()->greaterThanOrEqualTo($assignment->end_datetime))
        ) {
            throw ValidationException::withMessages([
                'assignment' => [
                    'Batas waktu assignment telah berakhir. Assignment otomatis menjadi Tidak Dikerjakan.',
                ],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        if (! $assignmentEmployee->canBeAccepted()) {

            throw ValidationException::withMessages([
                'assignment' => [
                    'Assignment tidak dapat diterima.',
                ],
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Save
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (

            $assignmentEmployee,

            $assignment,

            $employee,

            $user

        ) {

            $assignmentEmployee->update([

                'status' => 'Accepted',

                'accepted_at' => now(),

            ]);

            AssignmentLog::create([

                'assignment_id' => $assignment->id,

                'employee_id' => $employee->id,

                'user_id' => $user->id,

                'action' => 'EMPLOYEE_ACCEPTED',

                'description' => 'Employee accepted assignment.',

            ]);

        });

        $freshPivot = $assignmentEmployee->fresh(['assignment', 'employee.user']);
        $admins = User::query()->companyAdminsOf($employee->company_id)->get();
        Notification::send($admins, new AssignmentResponseUpdated($freshPivot, true));

        return $assignment->fresh([

            'office',

            'creator.employee',

            'employees',

            'logs',

        ]);

    }
    /*
    |--------------------------------------------------------------------------
    | Reject Assignment
    |--------------------------------------------------------------------------
    */

    public function reject(
        User $user,
        string $uuid,
        string $reason
    ): Assignment {

        $employee = $user->employee;

        $assignment = $this->find(
            $user,
            $uuid
        );

        $assignmentEmployee = AssignmentEmployee::query()

            ->where('assignment_id', $assignment->id)

            ->where('employee_id', $employee->id)

            ->firstOrFail();

        if (
            in_array($assignmentEmployee->review_status, ['Not Worked', 'Expired'], true)
            || ($assignment->end_datetime && now()->greaterThanOrEqualTo($assignment->end_datetime))
        ) {
            throw ValidationException::withMessages([
                'assignment' => [
                    'Batas waktu assignment telah berakhir. Assignment otomatis menjadi Tidak Dikerjakan.',
                ],
            ]);
        }

        if ($assignmentEmployee->status !== 'Assigned') {

            throw ValidationException::withMessages([
                'assignment' => [
                    'Assignment tidak dapat ditolak.',
                ],
            ]);

        }

        DB::transaction(function () use (

            $assignmentEmployee,

            $assignment,

            $employee,

            $user,
            $reason

        ) {

            $assignmentEmployee->update([

                'status' => 'Rejected',
                'rejection_reason' => trim($reason),

            ]);

            AssignmentLog::create([

                'assignment_id' => $assignment->id,

                'employee_id' => $employee->id,

                'user_id' => $user->id,

                'action' => 'EMPLOYEE_REJECTED',

                'description' => 'Employee rejected assignment. Alasan: '.trim($reason),

            ]);

        });

        $freshPivot = $assignmentEmployee->fresh(['assignment', 'employee.user']);
        $admins = User::query()->companyAdminsOf($employee->company_id)->get();
        Notification::send($admins, new AssignmentResponseUpdated($freshPivot, false));

        return $assignment->fresh([

            'office',

            'creator.employee',

            'employees',

            'logs',

        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Check In Assignment
    |--------------------------------------------------------------------------
    */

    public function checkIn(
        User $user,
        string $uuid,
        float $latitude,
        float $longitude,
        AttendanceService $attendanceService
    ): array {

        $employee = $user->employee;

        $assignment = $this->find(
            $user,
            $uuid
        );

        $assignmentEmployee = AssignmentEmployee::query()

            ->where('assignment_id', $assignment->id)

            ->where('employee_id', $employee->id)

            ->firstOrFail();

        if (! in_array($assignmentEmployee->status, $assignment->daily_attendance_enabled ? ['Accepted', 'In Progress'] : ['Accepted'], true)) {

            return [

                'success' => false,

                'message' => 'Assignment harus diterima terlebih dahulu sebelum check in.',

            ];

        }

        // Sesi assignment TERPISAH dari attendance harian. Employee boleh
        // mengerjakan lebih dari satu assignment dalam hari yang sama. Jika
        // attendance harian sudah berjalan (Office / assignment lain), jangan
        // membuat absensi kedua; tetap validasi geofence assignment lalu mulai
        // work session pada pivot assignment ini.
        if (! $assignment->daily_attendance_enabled && $attendanceService->hasAttendanceToday($employee)) {
            $location = app(AttendanceLocationService::class)
                ->validateAssignment($assignment, $latitude, $longitude);

            if (! ($location['allowed'] ?? false)) {
                return [
                    'success' => false,
                    'message' => 'You are outside the assignment area.',
                    'distance' => $location['distance'] ?? null,
                    'radius' => $location['radius'] ?? null,
                ];
            }

            $result = [
                'success' => true,
                'message' => 'Check in assignment berhasil.',
                'attendance' => $attendanceService->getTodayAnyAttendance($employee),
            ];
        } else {
            $result = $attendanceService->checkInAssignment(
                $employee,
                $assignment,
                $latitude,
                $longitude
            );

            if (! $result['success']) {
                return $result;
            }
        }

        DB::transaction(function () use (

            $assignmentEmployee,

            $assignment,

            $employee,

            $user,

            $result

        ) {

            $assignmentEmployee->update([

                'status' => 'In Progress',

                'started_at' => $assignmentEmployee->started_at ?? now(),
                'work_check_in_at' => $assignmentEmployee->work_check_in_at ?? now(),
                'work_check_out_at' => null,

            ]);

            AssignmentLog::create([

                'assignment_id' => $assignment->id,

                'employee_id' => $employee->id,

                'user_id' => $user->id,

                'action' => 'EMPLOYEE_CHECKED_IN',

                'description' => 'Employee checked in at assignment location.',

                'properties' => [
                    'attendance_date' => today()->toDateString(),
                    'attendance_status' => $result['attendance']->attendance_status ?? null,
                    'late_minutes' => (int) ($result['attendance']->late_minutes ?? 0),
                ],

            ]);

            /*
            |--------------------------------------------------------------------------
            | Assignment otomatis menjadi "In Progress" begitu ada employee
            | yang check-in, tanpa perlu diubah manual oleh admin.
            |--------------------------------------------------------------------------
            */

            if ($assignment->status === 'Assigned') {

                $assignment->update([
                    'status' => 'In Progress',
                ]);

            }

        });

        return $result;

    }

    /*
    |--------------------------------------------------------------------------
    | Check Out Assignment
    |--------------------------------------------------------------------------
    */

    public function checkOut(
        User $user,
        string $uuid,
        float $latitude,
        float $longitude,
        AttendanceService $attendanceService,
        ?string $workDescription = null,
        array $workPhotos = []
    ): array {

        $employee = $user->employee;
        $assignment = $this->find($user, $uuid);

        if ($assignment->daily_attendance_enabled) {
            $workDescription = trim((string) $workDescription);

            if (mb_strlen($workDescription) < 5) {
                throw ValidationException::withMessages([
                    'work_description' => ['Isi detail pekerjaan hari ini minimal 5 karakter sebelum Check Out.'],
                ]);
            }

            if (count($workPhotos) > 3) {
                throw ValidationException::withMessages([
                    'work_photos' => ['Maksimal 3 foto bukti pekerjaan per hari.'],
                ]);
            }
        }

        return DB::transaction(function () use (
            $attendanceService,
            $employee,
            $assignment,
            $latitude,
            $longitude,
            $workDescription,
            $workPhotos,
            $user
        ) {
            // Non-Daily Assignment: Check Out Assignment menutup sesi tugas,
            // bukan attendance harian. Attendance tetap berjalan sampai employee
            // melakukan Check Out dari menu Attendance.
            if (! $assignment->daily_attendance_enabled) {
                $assignmentEmployee = AssignmentEmployee::query()
                    ->where('assignment_id', $assignment->id)
                    ->where('employee_id', $employee->id)
                    ->firstOrFail();

                if (! $assignmentEmployee->completion_photo) {
                    return ['success' => false, 'message' => 'Upload dulu foto bukti & catatan hasil kerja sebelum check out assignment.'];
                }

                if ($assignmentEmployee->work_check_out_at) {
                    return ['success' => false, 'message' => 'Kamu sudah check out dari assignment ini.'];
                }

                $location = app(AttendanceLocationService::class)
                    ->validateAssignment($assignment, $latitude, $longitude);

                if (! ($location['allowed'] ?? false)) {
                    return [
                        'success' => false,
                        'message' => 'You are outside the assignment area.',
                        'distance' => $location['distance'] ?? null,
                        'radius' => $location['radius'] ?? null,
                    ];
                }

                $assignmentEmployee->update(['work_check_out_at' => now()]);

                AssignmentLog::create([
                    'assignment_id' => $assignment->id,
                    'employee_id' => $employee->id,
                    'user_id' => $user->id,
                    'action' => 'EMPLOYEE_CHECKED_OUT',
                    'description' => 'Employee checked out from assignment work session. Attendance harian tetap berjalan.',
                    'properties' => ['attendance_remains_open' => true],
                ]);

                $attendance = $attendanceService->getTodayAssignmentAttendance($employee, $assignment);

                return ['success' => true, 'message' => 'Check out assignment berhasil. Attendance harian tetap berjalan.', 'attendance' => $attendance];
            }

            $result = $attendanceService->checkOutAssignment(
                $employee, $assignment, $latitude, $longitude
            );

            if ($result['success'] ?? false) {
                $attendance = $result['attendance'];

                if ($assignment->daily_attendance_enabled) {
                    $storedPhotos = [];

                    foreach ($workPhotos as $photo) {
                        if ($photo instanceof UploadedFile) {
                            $storedPhotos[] = app(SecureFileService::class)->store(
                                $photo,
                                'assignment-daily-reports'
                            );
                        }
                    }

                    $attendance->update([
                        'daily_report_notes' => $workDescription,
                        'daily_report_photos' => $storedPhotos,
                    ]);

                    $attendance->refresh();
                    $result['attendance'] = $attendance;
                }

                AssignmentLog::create([
                    'assignment_id' => $assignment->id,
                    'employee_id' => $employee->id,
                    'user_id' => $user->id,
                    'action' => 'EMPLOYEE_CHECKED_OUT',
                    'description' => $assignment->daily_attendance_enabled
                        ? 'Employee checked out and submitted the daily work report.'
                        : 'Employee checked out from assignment location.',
                    'properties' => [
                        'attendance_date' => today()->toDateString(),
                        'work_minutes' => (int) ($result['attendance']->work_minutes ?? 0),
                        'early_leave_minutes' => (int) ($result['attendance']->early_leave_minutes ?? 0),
                        'overtime_minutes' => (int) ($result['attendance']->overtime_minutes ?? 0),
                        'daily_report_submitted' => (bool) $assignment->daily_attendance_enabled,
                        'daily_report_photo_count' => count($result['attendance']->daily_report_photos ?? []),
                    ],
                ]);
            }

            return $result;
        });

    }

    /*
    |--------------------------------------------------------------------------
    | Complete Assignment
    |--------------------------------------------------------------------------
    */

    public function complete(
        User $user,
        string $uuid,
        UploadedFile $photo,
        ?UploadedFile $photo2,
        string $completionNotes
    ): Assignment {

        $employee = $user->employee;

        $assignment = $this->find(
            $user,
            $uuid
        );

        $assignmentEmployee = AssignmentEmployee::query()

            ->where('assignment_id', $assignment->id)

            ->where('employee_id', $employee->id)

            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Submit pertama kali (belum pernah di-review sama sekali) VS
        | resubmit setelah company reject (Needs Revision). Dua alur ini
        | punya syarat & deadline yang berbeda, jadi tentukan lebih dulu.
        |--------------------------------------------------------------------------
        */

        $isResubmission = $assignmentEmployee->needsRevision();

        // Deadline assignment hanya berlaku untuk submit pertama.
        // Resubmit mengikuti revision_deadline_at + grace period sendiri.
        if (! $isResubmission) {
            $completionDeadline = $assignment->end_datetime?->copy();
            if ($completionDeadline && $assignment->daily_attendance_enabled) {
                // Daily Attendance hari terakhir masih boleh menyelesaikan
                // check-out + submit sampai batas final harian (23:00).
                $completionDeadline->setTime(23, 0, 0);
            }

            if ($completionDeadline && now()->greaterThan($completionDeadline)) {
                throw ValidationException::withMessages([
                    'assignment' => ['Batas waktu penyelesaian assignment sudah lewat.'],
                ]);
            }
        }

        // Attendance harian dan penyelesaian assignment adalah dua hal yang
        // berbeda. Employee tetap hanya boleh submit hasil pada hari terakhir,
        // tetapi ketidakhadiran / lupa Check Out pada hari sebelumnya tidak
        // boleh membuat assignment terkunci permanen. Semua kekurangan itu tetap
        // tercatat di kalender attendance dan dapat dilihat company.
        //
        // Jika hari terakhir adalah hari attendance wajib, sesi hari terakhir
        // wajib sudah Check Out sebelum hasil akhir dapat dikirim.
        if (! $isResubmission && $assignment->daily_attendance_enabled) {
            $lastDate = $assignment->end_datetime->copy()->startOfDay();
            if (today()->lt($lastDate)) {
                throw ValidationException::withMessages([
                    'assignment' => ['Assignment multi-hari belum memasuki hari terakhir.'],
                ]);
            }

            $calendar = app(WorkCalendarService::class);
            $lastDayRequired = $assignment->attendance_day_rule === 'EVERY_DAY'
                || $calendar->isWorkingDay($employee->company, $lastDate);

            if ($lastDayRequired) {
                $lastAttendanceCompleted = Attendance::query()
                    ->where('employee_id', $employee->id)
                    ->where('assignment_id', $assignment->id)
                    ->where('attendance_type', 'ASSIGNMENT')
                    ->whereDate('attendance_date', $lastDate)
                    ->where('is_checked_in', true)
                    ->where('is_checked_out', true)
                    ->exists();

                if (! $lastAttendanceCompleted) {
                    throw ValidationException::withMessages([
                        'assignment' => ['Check Out attendance hari terakhir terlebih dahulu sebelum mengirim hasil assignment.'],
                    ]);
                }
            }
        }

        // Dipakai baik untuk validasi guard di bawah maupun nanti masuk
        // ke dalam transaction -- dihitung sekali di sini biar tidak
        // dobel logic yang sama.
        $canSkipCheckIn = ! $isResubmission
            && $assignment->daily_attendance_enabled
            && $assignmentEmployee->status === 'Accepted'
            && $this->attendanceService->hasAttendanceToday($employee);

        if (! $isResubmission
            && ! $assignment->daily_attendance_enabled
            && $assignmentEmployee->work_check_in_at === null) {
            throw ValidationException::withMessages([
                'assignment' => ['Check In Assignment terlebih dahulu sebelum menyelesaikan pekerjaan.'],
            ]);
        }

        if ($isResubmission) {

            /*
            |--------------------------------------------------------------------------
            | Resubmit (revisi) -- HARUS masih berstatus 'Needs Revision'
            | dan belum kelewat toleransi 2 jam dari revision_deadline_at.
            | Lewat dari itu, employee sudah tidak bisa apa-apa lagi
            | (tunggu di-flip 'Expired' oleh scheduled job -- lihat
            | App\Console\Commands\ExpireAssignmentRevisions).
            |--------------------------------------------------------------------------
            */

            if ($assignmentEmployee->isPastRevisionGracePeriod()) {

                throw ValidationException::withMessages([
                    'assignment' => [
                        'Batas waktu revisi (termasuk toleransi keterlambatan) sudah lewat. Assignment ini sudah tidak bisa dikerjakan lagi.',
                    ],
                ]);

            }

        } elseif (! $assignmentEmployee->canSubmitCompletion()) {

            /*
            |--------------------------------------------------------------------------
            | Kalau absensi hari ini SUDAH tercatat (lewat Office ataupun
            | assignment lain -- absensi memang cuma boleh 1x per hari),
            | assignment yang masih "Accepted" ini boleh langsung
            | diselesaikan tanpa lewat tombol Check In terpisah lagi.
            | Status "In Progress" tetap dicatat otomatis di dalam
            | transaction di bawah supaya riwayat aktivitas & started_at
            | tetap konsisten.
            |--------------------------------------------------------------------------
            */

            if ($assignmentEmployee->status !== 'In Progress' && ! $canSkipCheckIn) {

                throw ValidationException::withMessages([
                    'assignment' => [
                        'Assignment belum bisa diselesaikan. Pastikan sudah check in.',
                    ],
                ]);

            }

        }

        $fileService = app(SecureFileService::class);

        $photoPath = $fileService->store($photo, 'assignments/completion');

        $photo2Path = $photo2
            ? $fileService->store($photo2, 'assignments/completion')
            : null;

        $company = $employee->company;

        $autoApprove = (bool) ($company?->assignment_auto_approve);

        $isLate = $isResubmission && $assignmentEmployee->isWithinLateRevisionGrace();

        DB::transaction(function () use (

            $assignmentEmployee,

            $assignment,

            $employee,

            $user,

            $photoPath,

            $photo2Path,

            $completionNotes,

            $canSkipCheckIn,

            $isResubmission,

            $autoApprove,

            $isLate

        ) {

            if ($canSkipCheckIn) {

                $assignmentEmployee->update([

                    'status' => 'In Progress',

                    'started_at' => now(),
                    'work_check_in_at' => $assignmentEmployee->work_check_in_at ?? now(),
                    'work_check_out_at' => null,

                ]);

                AssignmentLog::create([

                    'assignment_id' => $assignment->id,

                    'employee_id' => $employee->id,

                    'user_id' => $user->id,

                    'action' => 'EMPLOYEE_AUTO_CHECKED_IN',

                    'description' => 'Check-in otomatis (absensi hari ini sudah tercatat).',

                ]);

                if ($assignment->status === 'Assigned') {

                    $assignment->update([
                        'status' => 'In Progress',
                    ]);

                }

            }

            $newReviewStatus = $autoApprove ? 'Approved' : 'Pending Review';

            $assignmentEmployee->update([

                'status' => 'Completed',

                'finished_at' => now(),

                // Untuk assignment non-Daily, Submit/Selesaikan Assignment
                // adalah akhir sesi kerja assignment. Attendance harian tetap
                // terpisah dan tidak ikut Check Out di sini.
                'work_check_out_at' => ! $assignment->daily_attendance_enabled
                    ? ($assignmentEmployee->work_check_out_at ?? now())
                    : $assignmentEmployee->work_check_out_at,

                'completion_photo' => $photoPath,

                'completion_photo_2' => $photo2Path,

                'completion_notes' => $completionNotes,

                'review_status' => $newReviewStatus,

                'review_notes' => null,

                'reviewed_by' => $autoApprove ? null : $assignmentEmployee->reviewed_by,

                'reviewed_at' => $autoApprove ? now() : null,

                'revision_deadline_at' => null,

                'is_late_revision' => $isLate,

                'revision_count' => $isResubmission
                    ? $assignmentEmployee->revision_count + 1
                    : $assignmentEmployee->revision_count,

            ]);

            AssignmentLog::create([

                'assignment_id' => $assignment->id,

                'employee_id' => $employee->id,

                'user_id' => $user->id,

                'action' => $isResubmission ? 'EMPLOYEE_RESUBMITTED' : 'EMPLOYEE_COMPLETED',

                'description' => $isResubmission
                    ? ('Employee resubmit hasil revisi.'.($isLate ? ' (Late Pengerjaan -- lewat batas waktu revisi)' : ''))
                    : 'Employee completed assignment with photo proof and notes.',

                'properties' => [
                    'evidence_count' => $photo2Path ? 2 : 1,
                    'late_revision' => $isLate,
                    'work_session_closed' => ! $assignment->daily_attendance_enabled,
                    'work_check_in_at' => optional($assignmentEmployee->work_check_in_at)->toDateTimeString(),
                    'work_check_out_at' => optional($assignmentEmployee->work_check_out_at)->toDateTimeString(),
                ],

            ]);

            if ($autoApprove) {

                AssignmentLog::create([

                    'assignment_id' => $assignment->id,

                    'employee_id' => $employee->id,

                    'user_id' => $user->id,

                    'action' => 'AUTO_APPROVED',

                    'description' => 'Hasil kerja otomatis di-approve (mode Auto Approve aktif).',

                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | Assignment otomatis menjadi "Completed" begitu SEMUA employee yang
            | ditugaskan sudah menyelesaikan bagiannya masing-masing.
            |--------------------------------------------------------------------------
            */

            $stillPending = AssignmentEmployee::query()

                ->where('assignment_id', $assignment->id)

                ->whereNotIn('status', ['Completed', 'Cancelled'])

                ->exists();

            if (! $stillPending && in_array($assignment->status, ['Assigned', 'In Progress'])) {

                $assignment->update([
                    'status' => 'Completed',
                ]);

            }

        });

        /*
        |--------------------------------------------------------------------------
        | Notifikasi ke SEMUA admin company -- ada laporan yang perlu
        | direview & di-approve/reject. Sengaja dikirim SETELAH transaksi
        | commit (bukan di dalamnya), dan SENGAJA dilewati kalau mode
        | Auto Approve aktif -- karena di kondisi itu tidak ada tindakan
        | apa pun yang perlu company lakukan (sudah otomatis Approved).
        |--------------------------------------------------------------------------
        */

        if (! $autoApprove) {

            $admins = User::query()
                ->companyAdminsOf($employee->company_id)
                ->get();

            Notification::send(
                $admins,
                new AssignmentCompletionSubmitted($assignmentEmployee->fresh(), $isResubmission)
            );

        }

        return $assignment->fresh([

            'office',

            'creator.employee',

            'employees',

            'logs',

        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Active Assignment
    |--------------------------------------------------------------------------
    */

    public function active(
        User $user
    ) {

        return $user

            ->employee

            ?->currentAssignment;

    }

    /*
    |--------------------------------------------------------------------------
    | Today's Assignment
    |--------------------------------------------------------------------------
    */

    public function today(User $user)
    {
        $this->deadlineSynchronizer->sync($user);

        return Assignment::query()
            ->with(['office', 'employees'])
            ->whereHas('employees', function ($query) use ($user) {
                $query->where('employees.id', $user->employee->id);
            })
            ->where('assignments.status', '!=', 'Draft')
            ->whereDate('start_datetime', '<=', today())
            ->whereDate('end_datetime', '>=', today())
            ->orderBy('start_datetime')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Employee Assignment Statistics
    |--------------------------------------------------------------------------
    */

    public function statistics(
        User $user
    ): array {
        $employee = $user->employee;
        $this->deadlineSynchronizer->sync($user);

        return $this->assignmentStatistics->summarize($employee->id);
    }
}
