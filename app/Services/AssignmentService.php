<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\AssignmentAttachment;
use App\Models\AssignmentEmployee;
use App\Models\AssignmentLog;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Office;
use App\Notifications\AssignmentReviewUpdated;
use App\Support\PolygonDecoder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AssignmentService extends BaseService
{
    public function __construct(
        private readonly AssignmentAssignedNotifier $assignmentNotifier,
        private readonly CompanyAssignmentQuery $assignmentQuery,
        private readonly PolygonDecoder $polygonDecoder,
        private readonly CompanyAssignmentStatistics $assignmentStatistics,
    ) {}

    /**
     * Get Assignment List
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        // Self-healing fallback untuk deployment serverless: kalau scheduler eksternal
        // terlambat/terlewat, request Assignment berikutnya tetap mengaktifkan Draft
        // yang sudah jatuh tempo DAN menjalankan notifikasi employee.
        $this->activateScheduledDrafts();
        $this->repairLegacyDailyAttendanceNotWorked();
        $this->repairApprovedAssignmentStatuses();

        return $this->assignmentQuery->build($filters)
            ->paginate($filters['per_page'] ?? 10)
            ->withQueryString();
    }

    /**
     * Company-facing statistics. Every metric counts unique assignments,
     * never employee pivot rows, so multi-employee assignments do not inflate
     * Pending Review / Needs Revision numbers.
     */
    public function companyStatistics(): array
    {
        $this->repairLegacyDailyAttendanceNotWorked();
        $this->repairApprovedAssignmentStatuses();

        return $this->assignmentStatistics->build();
    }

    /**
     * Repair data dari logic Phase 3 lama yang menandai seluruh Daily Attendance
     * sebagai Not Worked setelah deadline walaupun employee sebenarnya pernah
     * Check In. Revision yang benar-benar expired tidak disentuh.
     */
    private function repairLegacyDailyAttendanceNotWorked(): void
    {
        static $repairedThisRequest = false;
        if ($repairedThisRequest) {
            return;
        }
        $repairedThisRequest = true;

        $assignmentIds = Assignment::query()
            ->forCurrentCompany()
            ->where('daily_attendance_enabled', true)
            ->pluck('id');

        if ($assignmentIds->isEmpty()) {
            return;
        }

        $rows = AssignmentEmployee::query()
            ->whereIn('assignment_id', $assignmentIds)
            ->where('review_status', AssignmentEmployee::REVIEW_NOT_WORKED)
            // Not Worked akibat revision expiry tetap final dan tidak diperbaiki.
            ->whereNull('revision_deadline_at')
            ->get();

        foreach ($rows as $row) {
            $hasWorked = Attendance::query()
                ->where('assignment_id', $row->assignment_id)
                ->where('employee_id', $row->employee_id)
                ->where('attendance_type', 'ASSIGNMENT')
                ->where('is_checked_in', true)
                ->exists();

            if (! $hasWorked) {
                continue;
            }

            $row->update([
                // Periode kerja sudah selesai dan record sekarang masuk review.
                // Status operasional employee juga harus terminal agar parent
                // assignment tidak tertinggal sebagai In Progress.
                'status' => AssignmentEmployee::STATUS_COMPLETED,
                'review_status' => AssignmentEmployee::REVIEW_PENDING,
                'review_notes' => 'Status diperbaiki otomatis: employee memiliki riwayat kerja Daily Attendance dan menunggu review company.',
                'reviewed_at' => null,
            ]);

            if ($row->assignment) {
                $this->syncParentAssignmentCompletedStatus($row->assignment);
            }

            AssignmentLog::create([
                'assignment_id' => $row->assignment_id,
                'employee_id' => $row->employee_id,
                'user_id' => null,
                'action' => 'DAILY_ATTENDANCE_STATUS_REPAIRED',
                'description' => 'Status Not Worked lama dikoreksi menjadi Pending Review karena terdapat attendance kerja.',
            ]);
        }
    }

    /**
     * Find Assignment
     */
    public function find(int $id): Assignment
    {
        return Assignment::query()
            ->forCurrentCompany()
            ->with([
                'office',
                'creator.employee',
                'employees.currentEmployment.position',
                'employees.currentEmployment.office',
                'logs.user.employee',
                'logs.employee',
                'attachments',
            ])
            ->findOrFail($id);
    }

    /**
     * Create Form Data
     */
    public function createFormData(): array
    {
        return [

            'offices' => Office::query()
                ->forCurrentCompany()
                ->orderBy('name')
                ->get(),

            'employees' => Employee::query()
                ->forCurrentCompany()
                ->with([
                    'currentEmployment.position',
                    'currentEmployment.office',
                    'assignmentEmployees.assignment',
                ])
                ->where('is_active', true)
                ->orderBy('full_name')
                ->get(),

            'priorities' => [
                'Low',
                'Medium',
                'High',
                'Critical',
            ],

            'types' => [
                'Maintenance',
                'Installation',
                'Inspection',
                'Survey',
                'Emergency',
            ],

            /*
            |--------------------------------------------------------------------------
            | "In Progress" & "Completed" tidak dipilih manual — otomatis mengikuti
            | aksi employee (check-in / selesai) atau job schedule (Draft -> Assigned).
            |--------------------------------------------------------------------------
            */

            'statuses' => [
                Assignment::STATUS_DRAFT,
                Assignment::STATUS_ASSIGNED,
                Assignment::STATUS_CANCELLED,
            ],

        ];
    }

    /**
     * Create Assignment
     */
    public function create(array $data, int $userId): Assignment
    {
        $this->fillCompany($data);

        /*
        |--------------------------------------------------------------------------
        | Retry Otomatis
        |--------------------------------------------------------------------------
        |
        | Sebagai lapisan pengaman tambahan selain lockForUpdate() di
        | generateAssignmentNumber(): kalau dua request benar-benar terjadi
        | bersamaan dan tetap menghasilkan assignment_number yang sama,
        | percobaan diulang otomatis dengan nomor berikutnya alih-alih
        | menampilkan error ke user.
        |
        */

        $attempts = 0;

        while (true) {

            $attempts++;

            try {

                return DB::transaction(function () use ($data, $userId) {

                    /*
                    |--------------------------------------------------------------------------
                    | Create Assignment
                    |--------------------------------------------------------------------------
                    */

                    $polygon = $this->decodePolygon($data['polygon'] ?? null);

                    $assignment = Assignment::create([

                        'company_id' => $data['company_id'],

                        'assignment_number' => $this->generateAssignmentNumber(),

                        'title' => $data['title'],

                        'description' => $data['description'] ?? null,

                        'office_id' => $data['office_id'],

                        'location_name' => $data['location_name'],

                        'address' => $data['address'] ?? null,

                        'latitude' => $data['latitude'],

                        'longitude' => $data['longitude'],

                        'radius' => $polygon ? null : ($data['radius'] ?? null),

                        'polygon' => $polygon,

                        'priority' => $data['priority'],

                        'assignment_type' => $data['assignment_type'],

                        'status' => $data['status'],

                        'start_datetime' => $data['start_datetime'],

                        'end_datetime' => $data['end_datetime'],

                        'daily_attendance_enabled' => (bool) ($data['daily_attendance_enabled'] ?? false),

                        'attendance_day_rule' => $data['attendance_day_rule'] ?? 'WORK_CALENDAR',

                        'created_by' => $userId,

                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Employees
                    |--------------------------------------------------------------------------
                    */

                    if (! empty($data['employees'])) {

                        $this->assignEmployees(
                            $assignment,
                            $data['employees']
                        );

                    }

                    $this->storeAttachments($assignment, $data['attachments'] ?? [], $userId);

                    /*
                    |--------------------------------------------------------------------------
                    | Assignment Log
                    |--------------------------------------------------------------------------
                    */

                    $this->addLog(
                        assignment: $assignment,
                        employeeId: null,
                        userId: $userId,
                        action: 'ASSIGNMENT_CREATED',
                        description: 'Assignment created.'
                    );

                    return $assignment->load([
                        'office',
                        'creator',
                        'employees',
                        'logs',
                        'attachments',
                    ]);

                });

            } catch (UniqueConstraintViolationException $exception) {

                if (
                    $attempts >= 5
                    || ! str_contains($exception->getMessage(), 'assignment_number')
                ) {
                    throw $exception;
                }

                // Nomor bentrok (race condition) — coba lagi dengan nomor berikutnya.
                continue;

            }

        }
    }

    /**
     * Update Assignment
     */
    public function update(Assignment $assignment, array $data): Assignment
    {
        $this->authorizeCompany($assignment);

        return DB::transaction(function () use ($assignment, $data) {

            $originalStatus = $assignment->status;

            /*
            |--------------------------------------------------------------------------
            | Update Assignment
            |--------------------------------------------------------------------------
            |
            | "In Progress" & "Completed" adalah status otomatis (dikendalikan oleh
            | aksi employee / job schedule), bukan pilihan manual dari form edit.
            | Kalau data yang masuk mencoba mengubah status Draft/Assigned/Cancelled
            | menjadi In Progress/Completed, abaikan dan pertahankan status lama.
            |
            */

            $status = $data['status'];

            $automaticStatuses = [Assignment::STATUS_IN_PROGRESS, Assignment::STATUS_COMPLETED];

            if (
                in_array($status, $automaticStatuses)
                && ! in_array($assignment->status, $automaticStatuses)
            ) {
                $status = $assignment->status;
            }

            $polygon = $this->decodePolygon($data['polygon'] ?? null);

            $assignment->update([

                'title' => $data['title'],

                'description' => $data['description'] ?? null,

                'office_id' => $data['office_id'],

                'location_name' => $data['location_name'],

                'address' => $data['address'] ?? null,

                'latitude' => $data['latitude'],

                'longitude' => $data['longitude'],

                'radius' => $polygon ? null : ($data['radius'] ?? null),

                'polygon' => $polygon,

                'priority' => $data['priority'],

                'assignment_type' => $data['assignment_type'],

                'status' => $status,

                'start_datetime' => $data['start_datetime'],

                'end_datetime' => $data['end_datetime'],

                'daily_attendance_enabled' => (bool) ($data['daily_attendance_enabled'] ?? false),

                'attendance_day_rule' => $data['attendance_day_rule'] ?? 'WORK_CALENDAR',

            ]);

            /*
            |--------------------------------------------------------------------------
            | Employees
            |--------------------------------------------------------------------------
            */

            $this->syncEmployees(
                $assignment,
                $data['employees'] ?? []
            );

            // Draft -> Assigned secara manual harus memberi notifikasi ke
            // SEMUA employee existing, bukan hanya employee yang baru ditambah.
            if ($originalStatus === Assignment::STATUS_DRAFT
                && $assignment->status === Assignment::STATUS_ASSIGNED
            ) {
                $assignment->assignmentEmployees()
                    ->with(['assignment', 'employee.user'])
                    ->get()
                    ->each(function (AssignmentEmployee $row) {
                        $this->assignmentNotifier->send($row);
                    });
            }

            $this->storeAttachments($assignment, $data['attachments'] ?? [], Auth::id());

            /*
            |--------------------------------------------------------------------------
            | Log
            |--------------------------------------------------------------------------
            */

            $this->addLog(
                assignment: $assignment,
                employeeId: null,
                userId: Auth::id(),
                action: 'ASSIGNMENT_UPDATED',
                description: 'Assignment updated.'
            );

            return $assignment->fresh([
                'office',
                'creator',
                'employees',
                'logs',
                'attachments',
            ]);

        });
    }

    private function storeAttachments(Assignment $assignment, array $files, ?int $userId): void
    {
        $validFiles = array_values(array_filter(
            $files,
            fn ($file) => $file instanceof UploadedFile
        ));

        if (empty($validFiles)) {
            return;
        }

        $existingCount = $assignment->attachments()->count();
        $incomingCount = count($validFiles);

        if ($incomingCount > 5 || ($existingCount + $incomingCount) > 5) {
            throw ValidationException::withMessages([
                'attachments' => [sprintf(
                    'Total lampiran instruksi maksimal 5 file. Saat ini sudah ada %d file.',
                    $existingCount
                )],
            ]);
        }

        $fileService = app(SecureFileService::class);

        foreach ($validFiles as $file) {
            $path = $fileService->store($file, 'assignments/instructions');
            AssignmentAttachment::create([
                'assignment_id' => $assignment->id,
                'uploaded_by' => $userId,
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize() ?: 0,
            ]);

            $this->addLog(
                assignment: $assignment, employeeId: null, userId: $userId,
                action: 'ATTACHMENT_ADDED',
                description: 'Lampiran instruksi ditambahkan.'
            );
        }
    }

    /**
     * Delete Assignment
     */
    public function delete(Assignment $assignment): bool
    {
        $this->authorizeCompany($assignment);

        return DB::transaction(function () use ($assignment) {

            $this->addLog(
                assignment: $assignment,
                employeeId: null,
                userId: Auth::id(),
                action: 'ASSIGNMENT_DELETED',
                description: 'Assignment deleted.'
            );

            return (bool) $assignment->delete();

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Assign Employees (Create)
    |--------------------------------------------------------------------------
    */

    private function assignEmployees(Assignment $assignment, array $employeeIds): void
    {
        if (empty($employeeIds)) {
            return;
        }

        foreach ($employeeIds as $employeeId) {

            $assignmentEmployee = AssignmentEmployee::create([

                'assignment_id' => $assignment->id,

                'employee_id' => $employeeId,

                'status' => AssignmentEmployee::STATUS_ASSIGNED,

                'assigned_at' => now(),

            ]);

            if (in_array($assignment->status, [Assignment::STATUS_ASSIGNED, Assignment::STATUS_IN_PROGRESS], true)) {
                $assignmentEmployee->load(['assignment', 'employee.user']);
                $this->assignmentNotifier->send($assignmentEmployee);
            }

            $this->addLog(
                assignment: $assignment,
                employeeId: $employeeId,
                userId: null,
                action: 'EMPLOYEE_ASSIGNED',
                description: 'Employee assigned.'
            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Sync Employees (Update)
    |--------------------------------------------------------------------------
    */

    private function syncEmployees(Assignment $assignment, array $employeeIds): void
    {
        $existingEmployeeIds = $assignment->assignmentEmployees()->pluck('employee_id')->map(fn ($id) => (int) $id)->all();
        $syncData = [];

        foreach ($employeeIds as $employeeId) {

            /*
            |--------------------------------------------------------------------------
            | Jika employee sudah ada sebelumnya,
            | jangan reset status & tanggal.
            |--------------------------------------------------------------------------
            */

            $existing = $assignment
                ->assignmentEmployees()
                ->where('employee_id', $employeeId)
                ->first();

            $syncData[$employeeId] = [

                'status' => $existing?->status ?? AssignmentEmployee::STATUS_ASSIGNED,

                'assigned_at' => $existing?->assigned_at ?? now(),

                'accepted_at' => $existing?->accepted_at,

                'finished_at' => $existing?->finished_at,

            ];

        }

        $assignment->employees()->sync($syncData);

        if (in_array($assignment->status, [Assignment::STATUS_ASSIGNED, Assignment::STATUS_IN_PROGRESS], true)) {
            $newEmployeeIds = array_values(array_diff(array_map('intval', $employeeIds), $existingEmployeeIds));
            if (! empty($newEmployeeIds)) {
                AssignmentEmployee::query()
                    ->with(['assignment', 'employee.user'])
                    ->where('assignment_id', $assignment->id)
                    ->whereIn('employee_id', $newEmployeeIds)
                    ->get()
                    ->each(function (AssignmentEmployee $row) {
                        $this->assignmentNotifier->send($row);
                    });
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Add Single Employee (reactive, dipakai oleh Livewire EmployeeManager)
    |--------------------------------------------------------------------------
    */

    public function addEmployee(Assignment $assignment, int $employeeId): void
    {
        $this->authorizeCompany($assignment);

        DB::transaction(function () use ($assignment, $employeeId) {

            $exists = $assignment
                ->assignmentEmployees()
                ->where('employee_id', $employeeId)
                ->exists();

            if ($exists) {
                return;
            }

            $assignmentEmployee = AssignmentEmployee::create([

                'assignment_id' => $assignment->id,

                'employee_id' => $employeeId,

                'status' => AssignmentEmployee::STATUS_ASSIGNED,

                'assigned_at' => now(),

            ]);

            if (in_array($assignment->status, [Assignment::STATUS_ASSIGNED, Assignment::STATUS_IN_PROGRESS], true)) {
                $assignmentEmployee->load(['assignment', 'employee.user']);
                $this->assignmentNotifier->send($assignmentEmployee);
            }

            $this->addLog(
                assignment: $assignment,
                employeeId: $employeeId,
                userId: Auth::id(),
                action: 'EMPLOYEE_ASSIGNED',
                description: 'Employee assigned.'
            );

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Remove Single Employee (reactive, dipakai oleh Livewire EmployeeManager)
    |--------------------------------------------------------------------------
    */

    public function removeEmployee(Assignment $assignment, int $employeeId): void
    {
        $this->authorizeCompany($assignment);

        DB::transaction(function () use ($assignment, $employeeId) {

            $assignment
                ->assignmentEmployees()
                ->where('employee_id', $employeeId)
                ->delete();

            $this->addLog(
                assignment: $assignment,
                employeeId: $employeeId,
                userId: Auth::id(),
                action: 'EMPLOYEE_REMOVED',
                description: 'Employee removed.'
            );

        });
    }

    /**
     * Add Assignment Log
     */
    private function addLog(
        Assignment $assignment,
        ?int $employeeId,
        ?int $userId,
        string $action,
        ?string $description = null,
        array $properties = []
    ): void {
        AssignmentLog::create([

            'assignment_id' => $assignment->id,

            'employee_id' => $employeeId,

            'user_id' => $userId,

            'action' => $action,

            'description' => $description,

            'properties' => empty($properties) ? null : $properties,

        ]);
    }

    /**
     * Sinkronkan status assignment induk dengan status operasional seluruh employee.
     * Assignment menjadi Completed bila tidak ada lagi employee yang masih aktif.
     */
    private function syncParentAssignmentCompletedStatus(Assignment $assignment): void
    {
        if (! in_array($assignment->status, [Assignment::STATUS_ASSIGNED, Assignment::STATUS_IN_PROGRESS], true)) {
            return;
        }

        $stillPending = AssignmentEmployee::query()
            ->where('assignment_id', $assignment->id)
            ->whereNotIn('status', [AssignmentEmployee::STATUS_COMPLETED, AssignmentEmployee::STATUS_CANCELLED])
            ->exists();

        if (! $stillPending) {
            $assignment->update(['status' => Assignment::STATUS_COMPLETED]);
        }
    }

    /**
     * Self-healing untuk data lama yang review_status-nya sudah Approved tetapi
     * status employee/assignment induk masih In Progress. Ini membuat list,
     * statistik, web, dan mobile kembali konsisten tanpa edit DB manual.
     */
    private function repairApprovedAssignmentStatuses(): void
    {
        static $repairedApprovedThisRequest = false;
        if ($repairedApprovedThisRequest) {
            return;
        }
        $repairedApprovedThisRequest = true;

        $assignmentIds = Assignment::query()
            ->forCurrentCompany()
            ->whereIn('status', [Assignment::STATUS_ASSIGNED, Assignment::STATUS_IN_PROGRESS])
            ->pluck('id');

        if ($assignmentIds->isEmpty()) {
            return;
        }

        AssignmentEmployee::query()
            ->whereIn('assignment_id', $assignmentIds)
            ->where('review_status', AssignmentEmployee::REVIEW_APPROVED)
            ->where('status', '!=', AssignmentEmployee::STATUS_COMPLETED)
            ->update(['status' => AssignmentEmployee::STATUS_COMPLETED]);

        $assignments = Assignment::query()
            ->forCurrentCompany()
            ->whereIn('id', $assignmentIds)
            ->whereIn('status', [Assignment::STATUS_ASSIGNED, Assignment::STATUS_IN_PROGRESS])
            ->get();

        foreach ($assignments as $assignment) {
            $this->syncParentAssignmentCompletedStatus($assignment);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Approve / Reject Hasil Kerja (Review)
    |--------------------------------------------------------------------------
    |
    | Company mengecek foto + catatan yang di-submit employee lewat
    | EmployeeAssignmentService::complete(), lalu approve (beres) atau
    | reject (Needs Revision -- employee harus resubmit sebelum
    | revision_deadline_at + toleransi 30 menit, lihat AssignmentEmployee
    | model & App\Console\Commands\ExpireAssignmentRevisions).
    |
    */

    public function approveCompletion(Assignment $assignment, int $employeeId, int $reviewerUserId): AssignmentEmployee
    {
        $this->authorizeCompany($assignment);

        $assignmentEmployee = AssignmentEmployee::query()

            ->where('assignment_id', $assignment->id)

            ->where('employee_id', $employeeId)

            ->firstOrFail();

        if (! in_array($assignmentEmployee->review_status, [AssignmentEmployee::REVIEW_PENDING, AssignmentEmployee::REVIEW_NEEDS_REVISION], true)) {

            throw ValidationException::withMessages([
                'review' => ['Hasil kerja ini tidak dalam status yang bisa di-approve.'],
            ]);

        }

        DB::transaction(function () use ($assignment, $assignmentEmployee, $employeeId, $reviewerUserId) {

            $assignmentEmployee->update([

                // Approval adalah terminal state pekerjaan employee.  Jangan hanya
                // mengubah review_status karena data Daily Attendance legacy bisa
                // masih menyimpan status operasional "In Progress".
                'status' => AssignmentEmployee::STATUS_COMPLETED,

                'review_status' => AssignmentEmployee::REVIEW_APPROVED,

                'reviewed_by' => $reviewerUserId,

                'reviewed_at' => now(),

                'revision_deadline_at' => null,

            ]);

            $this->addLog(
                assignment: $assignment,
                employeeId: $employeeId,
                userId: $reviewerUserId,
                action: 'COMPLETION_APPROVED',
                description: 'Hasil kerja disetujui company.'
            );

            $this->syncParentAssignmentCompletedStatus($assignment);

        });

        $fresh = $assignmentEmployee->fresh(['assignment', 'employee.user']);
        $fresh->employee?->user?->notify(new AssignmentReviewUpdated($fresh, true));

        return $fresh;
    }

    public function rejectCompletion(
        Assignment $assignment,
        int $employeeId,
        int $reviewerUserId,
        string $reviewNotes,
        ?int $revisionMinutesOverride = null
    ): AssignmentEmployee {
        $this->authorizeCompany($assignment);

        $assignmentEmployee = AssignmentEmployee::query()

            ->where('assignment_id', $assignment->id)

            ->where('employee_id', $employeeId)

            ->firstOrFail();

        if (! in_array($assignmentEmployee->review_status, [AssignmentEmployee::REVIEW_PENDING, AssignmentEmployee::REVIEW_NEEDS_REVISION], true)) {

            throw ValidationException::withMessages([
                'review' => ['Hasil kerja ini tidak dalam status yang bisa di-reject.'],
            ]);

        }

        $revisionMinutes = $revisionMinutesOverride
            ?? $assignment->company?->assignment_revision_minutes
            ?? 1440;

        DB::transaction(function () use (
            $assignment,
            $assignmentEmployee,
            $employeeId,
            $reviewerUserId,
            $reviewNotes,
            $revisionMinutes
        ) {

            $assignmentEmployee->update([

                'review_status' => AssignmentEmployee::REVIEW_NEEDS_REVISION,

                'review_notes' => $reviewNotes,

                'reviewed_by' => $reviewerUserId,

                'reviewed_at' => now(),

                'revision_deadline_at' => now()->addMinutes($revisionMinutes),

            ]);

            $this->addLog(
                assignment: $assignment,
                employeeId: $employeeId,
                userId: $reviewerUserId,
                action: 'COMPLETION_REJECTED',
                description: "Hasil kerja ditolak, perlu revisi: {$reviewNotes}"
            );

        });

        $fresh = $assignmentEmployee->fresh(['assignment', 'employee.user']);
        $fresh->employee?->user?->notify(new AssignmentReviewUpdated($fresh, false));

        return $fresh;
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Assignment Number
    |--------------------------------------------------------------------------
    */

    private function generateAssignmentNumber(): string
    {
        $prefix = 'ASM-'.now()->format('Ym');

        /*
        |--------------------------------------------------------------------------
        | withTrashed() WAJIB dipakai di sini.
        |--------------------------------------------------------------------------
        |
        | Kolom assignment_number UNIQUE di level database tidak mengecualikan
        | baris yang sudah soft-deleted, jadi nomor milik assignment yang sudah
        | dihapus tetap "terpakai". Kalau generator hanya mengecek data yang
        | belum dihapus, nomor yang sama bisa dicoba dipakai lagi dan bentrok
        | dengan constraint UNIQUE di database.
        |
        */

        $last = Assignment::query()
            ->withTrashed()
            ->forCurrentCompany()
            ->where('assignment_number', 'ILIKE', $prefix.'%')
            ->latest('id')
            ->lockForUpdate()
            ->first();

        if (! $last) {
            return $prefix.'-0001';
        }

        $number = (int) substr($last->assignment_number, -4);

        return sprintf('%s-%04d', $prefix, $number + 1);
    }

    /*
    |--------------------------------------------------------------------------
    | Decode Polygon
    |--------------------------------------------------------------------------
    */

    private function decodePolygon(?string $polygon): ?array
    {
        return $this->polygonDecoder->decode($polygon);
    }

    /*
    |--------------------------------------------------------------------------
    | Activate Scheduled Drafts
    |--------------------------------------------------------------------------
    |
    | Assignment dengan status Draft yang jadwalnya (start_datetime) sudah
    | tiba otomatis berubah menjadi Assigned, supaya employee mulai bisa
    | melihat & check-in assignment tersebut tanpa campur tangan admin.
    |
    */

    public function activateScheduledDrafts(): int
    {
        $assignments = Assignment::query()
            ->where('status', Assignment::STATUS_DRAFT)
            ->where('start_datetime', '<=', now())
            // Draft yang sudah melewati end_datetime jangan tiba-tiba baru
            // dipublish/notify terlambat. Tetap Draft agar Company bisa koreksi.
            ->where('end_datetime', '>', now())
            ->get();

        foreach ($assignments as $assignment) {

            $assignment->update([
                'status' => Assignment::STATUS_ASSIGNED,
            ]);
            $recipients = $assignment->assignmentEmployees()
                ->with(['assignment', 'employee.user'])
                ->get();

            $recipients->each(function (AssignmentEmployee $row) {
                $this->assignmentNotifier->send($row);
            });

            Log::info('Scheduled assignment activated.', [
                'assignment_id' => $assignment->id,
                'assignment_uuid' => $assignment->uuid,
                'recipient_count' => $recipients->count(),
            ]);

            $this->addLog(
                assignment: $assignment,
                employeeId: null,
                userId: null,
                action: 'ASSIGNMENT_AUTO_ASSIGNED',
                description: 'Assignment otomatis berubah menjadi Assigned karena jadwal sudah tiba.'
            );

        }

        // Recovery: kalau aktivasi sebelumnya sempat menyimpan status Assigned
        // tetapi notifikasi gagal/terputus, cron berikutnya akan backfill event
        // yang hilang (maksimal assignment 24 jam terakhir, idempotent).
        $this->assignmentNotifier->reconcileRecentlyAssigned();

        return $assignments->count();
    }
}
