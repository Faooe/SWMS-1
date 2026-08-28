<?php

namespace App\Services;

use App\Notifications\AssignmentReviewUpdated;
use App\Notifications\AssignmentAssigned;

use App\Models\Assignment;
use App\Models\AssignmentLog;
use App\Models\Employee;
use App\Models\Office;
use App\Models\AssignmentEmployee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class AssignmentService extends BaseService
{
    /**
     * Get Assignment List
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        // Self-healing fallback untuk deployment serverless: kalau scheduler eksternal
        // terlambat/terlewat, request Assignment berikutnya tetap mengaktifkan Draft
        // yang sudah jatuh tempo DAN menjalankan notifikasi employee.
        $this->activateScheduledDrafts();

        $query = Assignment::query()
            ->forCurrentCompany()
            ->with([
                'office',
                'creator',
                'employees',
                'logs',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $search = $filters['search'];

            $query->where(function ($q) use ($search) {

                $q->where('assignment_number', 'ILIKE', "%{$search}%")
                    ->orWhere('title', 'ILIKE', "%{$search}%")
                    ->orWhere('location_name', 'ILIKE', "%{$search}%");

            });

        }

        /*
        |--------------------------------------------------------------------------
        | Office
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['office'])) {
            $query->where('office_id', $filters['office']);
        }

        /*
        |--------------------------------------------------------------------------
        | Priority
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        /*
        |--------------------------------------------------------------------------
        | Date
        |--------------------------------------------------------------------------
        | Assignment ditampilkan bila jadwalnya bersinggungan dengan tanggal
        | yang dipilih. Ini sama dengan semantik filter My Assignment.
        */
        if (!empty($filters['date'])) {
            $query->whereDate('start_datetime', '<=', $filters['date'])
                ->whereDate('end_datetime', '>=', $filters['date']);
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['status'])) {
            $status = $filters['status'];

            /*
            |------------------------------------------------------------------
            | Status workflow untuk Company Admin
            |------------------------------------------------------------------
            |
            | Kolom assignments.status tetap dipakai sebagai status internal
            | (Draft/Assigned/In Progress/Completed/Cancelled). Namun UI Company
            | perlu membedakan hasil yang baru disubmit dari hasil yang sudah
            | di-approve. Karena itu Pending Review dan Needs Revision dibaca
            | dari assignment_employees.review_status.
            |
            */
            switch ($status) {
                case 'Draft':
                    $query->where('assignments.status', 'Draft');
                    break;

                case 'Assigned':
                    $query->where('assignments.status', 'Assigned')
                        ->whereDoesntHave('employees', function ($employeeQuery) {
                            $employeeQuery->whereIn('assignment_employees.review_status', [
                                'Pending Review',
                                'Needs Revision',
                            ]);
                        });
                    break;

                case 'In Progress':
                    // Tetap dipertahankan untuk Company Admin karena ini status
                    // operasional yang berguna untuk mengetahui assignment yang
                    // benar-benar sedang dikerjakan sebelum disubmit.
                    $query->where('assignments.status', 'In Progress')
                        ->whereDoesntHave('employees', function ($employeeQuery) {
                            $employeeQuery->whereIn('assignment_employees.review_status', [
                                'Pending Review',
                                'Needs Revision',
                            ]);
                        });
                    break;

                case 'Pending Review':
                    $query->whereHas('employees', function ($employeeQuery) {
                        $employeeQuery->where('assignment_employees.review_status', 'Pending Review');
                    });
                    break;

                case 'Needs Revision':
                    $query->whereHas('employees', function ($employeeQuery) {
                        $employeeQuery->where('assignment_employees.review_status', 'Needs Revision');
                    });
                    break;

                case 'Completed':
                    // Assignment global bisa sudah Completed segera setelah semua
                    // employee submit. Di UI Company, Completed baru berarti hasil
                    // sudah di-approve (manual maupun Auto Approve).
                    $query->where('assignments.status', 'Completed')
                        ->whereHas('employees', function ($employeeQuery) {
                            $employeeQuery->where('assignment_employees.review_status', 'Approved');
                        })
                        ->whereDoesntHave('employees', function ($employeeQuery) {
                            $employeeQuery->whereIn('assignment_employees.review_status', [
                                'Pending Review',
                                'Needs Revision',
                            ]);
                        });
                    break;

                case 'Cancelled':
                    $query->where('assignments.status', 'Cancelled');
                    break;

                default:
                    // Abaikan nilai filter yang tidak dikenal daripada
                    // memfilter kolom status dengan pseudo-status workflow.
                    break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $query->orderBy(
            $filters['sort'] ?? 'start_datetime',
            $filters['direction'] ?? 'desc'
        );

        return $query->paginate($filters['per_page'] ?? 10);
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
                'Draft',
                'Assigned',
                'Cancelled',
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

                        'radius' => $data['radius'],

                        'polygon' => $this->decodePolygon($data['polygon'] ?? null),

                        'priority' => $data['priority'],

                        'assignment_type' => $data['assignment_type'],

                        'status' => $data['status'],

                        'start_datetime' => $data['start_datetime'],

                        'end_datetime' => $data['end_datetime'],

                        'created_by' => $userId,

                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Employees
                    |--------------------------------------------------------------------------
                    */

                    if (!empty($data['employees'])) {

                        $this->assignEmployees(
                            $assignment,
                            $data['employees']
                        );

                    }

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
                    ]);

                });

            } catch (UniqueConstraintViolationException $exception) {

                if (
                    $attempts >= 5
                    || !str_contains($exception->getMessage(), 'assignment_number')
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

            $manualStatuses = ['Draft', 'Assigned', 'Cancelled'];
            $automaticStatuses = ['In Progress', 'Completed'];

            if (
                in_array($status, $automaticStatuses)
                && !in_array($assignment->status, $automaticStatuses)
            ) {
                $status = $assignment->status;
            }

            $assignment->update([

                'title' => $data['title'],

                'description' => $data['description'] ?? null,

                'office_id' => $data['office_id'],

                'location_name' => $data['location_name'],

                'address' => $data['address'] ?? null,

                'latitude' => $data['latitude'],

                'longitude' => $data['longitude'],

                'radius' => $data['radius'],

                'polygon' => $this->decodePolygon($data['polygon'] ?? null),

                'priority' => $data['priority'],

                'assignment_type' => $data['assignment_type'],

                'status' => $status,

                'start_datetime' => $data['start_datetime'],

                'end_datetime' => $data['end_datetime'],

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
            ]);

        });
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

                'status' => 'Assigned',

                'assigned_at' => now(),

            ]);

            if ($assignment->status === 'Assigned') {
                $assignmentEmployee->load(['assignment', 'employee.user']);
                $this->notifyAssignmentAssigned($assignmentEmployee);
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

                'status' => $existing?->status ?? 'Assigned',

                'assigned_at' => $existing?->assigned_at ?? now(),

                'accepted_at' => $existing?->accepted_at,

                'finished_at' => $existing?->finished_at,

            ];

        }

        $assignment->employees()->sync($syncData);

        if ($assignment->status === 'Assigned') {
            $newEmployeeIds = array_values(array_diff(array_map('intval', $employeeIds), $existingEmployeeIds));
            if (!empty($newEmployeeIds)) {
                AssignmentEmployee::query()
                    ->with(['assignment', 'employee.user'])
                    ->where('assignment_id', $assignment->id)
                    ->whereIn('employee_id', $newEmployeeIds)
                    ->get()
                    ->each(function (AssignmentEmployee $row) {
                        $this->notifyAssignmentAssigned($row);
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

                'status' => 'Assigned',

                'assigned_at' => now(),

            ]);

            if ($assignment->status === 'Assigned') {
                $assignmentEmployee->load(['assignment', 'employee.user']);
                $this->notifyAssignmentAssigned($assignmentEmployee);
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
    ): void
    {
        AssignmentLog::create([

            'assignment_id' => $assignment->id,

            'employee_id' => $employeeId,

            'user_id' => $userId,

            'action' => $action,

            'description' => $description,

            'properties' => empty($properties) ? null : $properties,

        ]);
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

        if (!in_array($assignmentEmployee->review_status, ['Pending Review', 'Needs Revision'], true)) {

            throw \Illuminate\Validation\ValidationException::withMessages([
                'review' => ['Hasil kerja ini tidak dalam status yang bisa di-approve.'],
            ]);

        }

        DB::transaction(function () use ($assignment, $assignmentEmployee, $employeeId, $reviewerUserId) {

            $assignmentEmployee->update([

                'review_status' => 'Approved',

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
    ): AssignmentEmployee
    {
        $this->authorizeCompany($assignment);

        $assignmentEmployee = AssignmentEmployee::query()

            ->where('assignment_id', $assignment->id)

            ->where('employee_id', $employeeId)

            ->firstOrFail();

        if (!in_array($assignmentEmployee->review_status, ['Pending Review', 'Needs Revision'], true)) {

            throw \Illuminate\Validation\ValidationException::withMessages([
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

                'review_status' => 'Needs Revision',

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
        $prefix = 'ASM-' . now()->format('Ym');

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
            ->where('assignment_number', 'ILIKE', $prefix . '%')
            ->latest('id')
            ->lockForUpdate()
            ->first();

        if (!$last) {
            return $prefix . '-0001';
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
        if (empty($polygon)) {
            return null;
        }

        $decoded = json_decode($polygon, true);

        return is_array($decoded) && count($decoded) >= 3
            ? $decoded
            : null;
    }

    /**
     * Kirim notifikasi Assignment Baru secara idempotent. Jalur direct
     * Assigned dan Draft -> Assigned memakai mekanisme yang sama.
     */
    private function notifyAssignmentAssigned(AssignmentEmployee $assignmentEmployee): void
    {
        $assignmentEmployee->loadMissing(['assignment', 'employee.user']);
        $user = $assignmentEmployee->employee?->user;

        if (! $user) {
            Log::warning('AssignmentAssigned dilewati: employee tidak punya user.', [
                'assignment_id' => $assignmentEmployee->assignment_id,
                'assignment_employee_id' => $assignmentEmployee->id,
                'employee_id' => $assignmentEmployee->employee_id,
            ]);
            return;
        }

        $alreadyExists = $user->notifications()
            ->where('type', AssignmentAssigned::class)
            ->get()
            ->contains(fn ($notification) =>
                (int) ($notification->data['assignment_employee_id'] ?? 0) === (int) $assignmentEmployee->id
            );

        if ($alreadyExists) {
            return;
        }

        $notification = new AssignmentAssigned($assignmentEmployee);

        try {
            $user->notify($notification);
        } catch (Throwable $exception) {
            Log::error('AssignmentAssigned gagal melalui notification pipeline.', [
                'user_id' => $user->id,
                'assignment_id' => $assignmentEmployee->assignment_id,
                'assignment_employee_id' => $assignmentEmployee->id,
                'error' => $exception->getMessage(),
            ]);

            // Kalau error terjadi sebelum channel database tersimpan, tetap
            // buat notification record agar badge/bell tidak kehilangan event.
            $databaseAlreadyExists = $user->notifications()
                ->where('type', AssignmentAssigned::class)
                ->get()
                ->contains(fn ($existing) =>
                    (int) ($existing->data['assignment_employee_id'] ?? 0) === (int) $assignmentEmployee->id
                );

            if (! $databaseAlreadyExists) {
                $user->notifications()->create([
                    'id' => (string) Str::uuid(),
                    'type' => AssignmentAssigned::class,
                    'data' => $notification->toArray($user),
                    'read_at' => null,
                ]);
            }
        }
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
            ->where('status', 'Draft')
            ->where('start_datetime', '<=', now())
            ->get();

        foreach ($assignments as $assignment) {

            $assignment->update([
                'status' => 'Assigned',
            ]);
            $recipients = $assignment->assignmentEmployees()
                ->with(['assignment', 'employee.user'])
                ->get();

            $recipients->each(function (AssignmentEmployee $row) {
                $this->notifyAssignmentAssigned($row);
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

        return $assignments->count();
    }
}