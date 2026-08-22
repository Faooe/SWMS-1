<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\AssignmentEmployee;
use App\Models\AssignmentLog;
use App\Models\User;
use App\Services\SecureFileService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\UploadedFile;

class EmployeeAssignmentService
{
    public function __construct(
        protected \App\Services\Attendance\AttendanceService $attendanceService
    ) {
    }

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

        $query = Assignment::query()

            ->with([

                'office',

                'creator.employee',

                'employees.currentEmployment.position',

                'employees.currentEmployment.office',

                'logs',

            ])

            ->whereHas(

                'employees',

                function ($query) use ($employee) {

                    $query->where(

                        'employees.id',

                        $employee->id

                    );

                }

            );

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $search = $filters['search'];

            $query->where(function ($q) use ($search) {

                $q->where(

                    'assignment_number',

                    'ILIKE',

                    "%{$search}%"

                )

                ->orWhere(

                    'title',

                    'ILIKE',

                    "%{$search}%"

                )

                ->orWhere(

                    'location_name',

                    'ILIKE',

                    "%{$search}%"

                );

            });

        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['status'])) {

            $query->where(

                'status',

                $filters['status']

            );

        }

        /*
        |--------------------------------------------------------------------------
        | Priority
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['priority'])) {

            $query->where(

                'priority',

                $filters['priority']

            );

        }

        /*
        |--------------------------------------------------------------------------
        | Date
        |--------------------------------------------------------------------------
        |
        | Sama semantiknya dengan today() di bawah (overlap): assignment
        | ikut muncul kalau tanggal yang dipilih berada di antara
        | start_datetime dan end_datetime-nya, bukan cuma yang PERSIS
        | mulai/berakhir di tanggal itu. Dipakai oleh mobile untuk filter
        | "My Assignment" (default: hari ini), disamakan gaya dengan
        | filter date_from/date_to di LeaveRequestService::getAll().
        */

        if (!empty($filters['date'])) {

            $query->whereDate(
                'start_datetime',
                '<=',
                $filters['date']
            )->whereDate(
                'end_datetime',
                '>=',
                $filters['date']
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Order
        |--------------------------------------------------------------------------
        */

        $query->orderByRaw("
            CASE status
                WHEN 'Assigned' THEN 1
                WHEN 'In Progress' THEN 2
                WHEN 'Completed' THEN 3
                WHEN 'Cancelled' THEN 4
                ELSE 5
            END
        ");

        $query->orderBy(

            'start_datetime',

            'asc'

        );

        return $query->paginate(

            $filters['per_page'] ?? 10

        );

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

        return Assignment::query()

            ->with([

                'office',

                'creator.employee',

                'employees.currentEmployment.position',

                'employees.currentEmployment.office',

                'logs.user.employee',

                'logs.employee',

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

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        if (! $assignmentEmployee->canBeAccepted()) {

            throw ValidationException::withMessages([
                'assignment' => [
                    'Assignment tidak dapat diterima.'
                ]
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
        string $uuid
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

        if ($assignmentEmployee->status !== 'Assigned') {

            throw ValidationException::withMessages([
                'assignment' => [
                    'Assignment tidak dapat ditolak.'
                ]
            ]);

        }

        DB::transaction(function () use (

            $assignmentEmployee,

            $assignment,

            $employee,

            $user

        ) {

            $assignmentEmployee->update([

                'status' => 'Rejected',

            ]);

            AssignmentLog::create([

                'assignment_id' => $assignment->id,

                'employee_id' => $employee->id,

                'user_id' => $user->id,

                'action' => 'EMPLOYEE_REJECTED',

                'description' => 'Employee rejected assignment.',

            ]);

        });

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
        \App\Services\Attendance\AttendanceService $attendanceService
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

        if ($assignmentEmployee->status !== 'Accepted') {

            return [

                'success' => false,

                'message' => 'Assignment harus diterima terlebih dahulu sebelum check in.',

            ];

        }

        $result = $attendanceService->checkInAssignment(

            $employee,

            $assignment,

            $latitude,

            $longitude

        );

        if (!$result['success']) {

            return $result;

        }

        DB::transaction(function () use (

            $assignmentEmployee,

            $assignment,

            $employee,

            $user

        ) {

            $assignmentEmployee->update([

                'status' => 'In Progress',

                'started_at' => now(),

            ]);

            AssignmentLog::create([

                'assignment_id' => $assignment->id,

                'employee_id' => $employee->id,

                'user_id' => $user->id,

                'action' => 'EMPLOYEE_CHECKED_IN',

                'description' => 'Employee checked in at assignment location.',

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
        \App\Services\Attendance\AttendanceService $attendanceService
    ): array {

        $employee = $user->employee;

        $assignment = $this->find(
            $user,
            $uuid
        );

        return $attendanceService->checkOutAssignment(

            $employee,

            $assignment,

            $latitude,

            $longitude

        );

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
        | punya syarat & efek yang beda -- ditentukan dulu di sini
        | sebelum masuk transaction.
        |--------------------------------------------------------------------------
        */

        $isResubmission = $assignmentEmployee->needsRevision();

        // Dipakai baik untuk validasi guard di bawah maupun nanti masuk
        // ke dalam transaction -- dihitung sekali di sini biar tidak
        // dobel logic yang sama.
        $canSkipCheckIn = !$isResubmission
            && $assignmentEmployee->status === 'Accepted'
            && $this->attendanceService->hasAttendanceToday($employee);

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
                        'Batas waktu revisi (termasuk toleransi keterlambatan) sudah lewat. Assignment ini sudah tidak bisa dikerjakan lagi.'
                    ]
                ]);

            }

        } elseif (!$assignmentEmployee->canSubmitCompletion()) {

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

            if ($assignmentEmployee->status !== 'In Progress' && !$canSkipCheckIn) {

                throw ValidationException::withMessages([
                    'assignment' => [
                        'Assignment belum bisa diselesaikan. Pastikan sudah check in.'
                    ]
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

            if (!$stillPending && in_array($assignment->status, ['Assigned', 'In Progress'])) {

                $assignment->update([
                    'status' => 'Completed',
                ]);

            }

        });

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
    return Assignment::query()
        ->with(['office', 'employees'])
        ->whereHas('employees', function ($query) use ($user) {
            $query->where('employees.id', $user->employee->id);
        })
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

        $query = AssignmentEmployee::query()

            ->where('employee_id', $employee->id);

        return [

            'total' => (clone $query)->count(),

            'assigned' => (clone $query)

                ->where('status', 'Assigned')

                ->count(),

            'progress' => (clone $query)

                ->whereIn('status', ['Accepted', 'In Progress'])

                ->count(),

            'completed' => (clone $query)

                ->where('status', 'Completed')

                ->count(),

            'cancelled' => (clone $query)

                ->where('status', 'Rejected')

                ->count(),

            /*
            |--------------------------------------------------------------------------
            | Statistik Review (dipakai widget "Perlu Revisi" di
            | Dashboard Employee & tab Performance -- lihat
            | AssignmentEmployee.review_status untuk penjelasan alur
            | Pending Review -> Approved / Needs Revision -> Expired)
            |--------------------------------------------------------------------------
            */

            'pending_review' => (clone $query)

                ->where('review_status', 'Pending Review')

                ->count(),

            'needs_revision' => (clone $query)

                ->where('review_status', 'Needs Revision')

                ->count(),

            'approved' => (clone $query)

                ->where('review_status', 'Approved')

                ->count(),

            'expired' => (clone $query)

                ->where('review_status', 'Expired')

                ->count(),

            'late_revision_count' => (clone $query)

                ->where('is_late_revision', true)

                ->count(),

        ];

    }

}