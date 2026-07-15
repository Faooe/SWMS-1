<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\AssignmentEmployee;
use App\Models\AssignmentLog;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class EmployeeAssignmentService
{
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
    UploadedFile $photo
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

        if ($assignmentEmployee->status !== 'In Progress') {

            throw ValidationException::withMessages([
                'assignment' => [
                    'Assignment belum bisa diselesaikan. Pastikan sudah check in.'
                ]
            ]);

        }

        $photoPath = $photo->store(
            'assignments/completion',
            'public'
        );

        DB::transaction(function () use (

            $assignmentEmployee,

            $assignment,

            $employee,

            $user,

            $photoPath

        ) {

            $assignmentEmployee->update([

                'status' => 'Completed',

                'finished_at' => now(),

                'completion_photo' => $photoPath,

            ]);

            AssignmentLog::create([

                'assignment_id' => $assignment->id,

                'employee_id' => $employee->id,

                'user_id' => $user->id,

                'action' => 'EMPLOYEE_COMPLETED',

                'description' => 'Employee completed assignment with photo proof.',

            ]);

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

        ];

    }

}