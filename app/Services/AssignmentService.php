<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\AssignmentLog;
use App\Models\Employee;
use App\Models\Office;
use App\Models\AssignmentEmployee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AssignmentService extends BaseService
{
    /**
     * Get Assignment List
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
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
        | Status
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
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

            AssignmentEmployee::create([

                'company_id' => $assignment->company_id,

                'assignment_id' => $assignment->id,

                'employee_id' => $employeeId,

                'status' => 'Assigned',

                'assigned_at' => now(),

            ]);

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

            'company_id' => $assignment->company_id,

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