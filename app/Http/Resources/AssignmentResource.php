<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * Catatan perbaikan: versi sebelumnya memakai nama field yang tidak
     * ada di model Assignment ($this->code, $this->start_date,
     * $this->end_date) sehingga nilainya selalu null di response API.
     * Sudah diperbaiki supaya sesuai kolom asli (assignment_number,
     * start_datetime, end_datetime) dan dilengkapi data yang dibutuhkan
     * Flutter: priority, assignment_type, status assignment khusus milik
     * employee yang sedang login (my_status / my_actions), serta log
     * aktivitas. Daftar employee memakai relasi 'employees' (bukan
     * 'assignmentEmployees') karena itu yang sudah di-eager-load oleh
     * AssignmentService & EmployeeAssignmentService, jadi tidak memicu
     * query tambahan (N+1) per baris di halaman list.
     */
    public function toArray(Request $request): array
    {
        /** @var \App\Models\User|null $user */
        $user = $request->user();

        $myPivot = null;

        if (
            $user
            && $user->employee_id
            && $this->relationLoaded('employees')
        ) {

            $myEmployee = $this->employees
                ->firstWhere('id', $user->employee_id);

            $myPivot = $myEmployee?->pivot;

        }

        /*
        |--------------------------------------------------------------------------
        | Sudah absen hari ini? (Office ataupun assignment lain -- absensi
        | memang dibatasi 1x per hari). Kalau sudah, tombol "Check In
        | Lokasi" di assignment ini tidak perlu ditampilkan lagi karena
        | pasti akan ditolak backend; employee bisa langsung upload foto
        | untuk menyelesaikan assignment.
        |--------------------------------------------------------------------------
        */

        $hasAttendanceToday = ($user && $user->employee)
            ? app(\App\Services\Attendance\AttendanceService::class)
                ->hasAttendanceToday($user->employee)
            : false;

        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'assignment_number' => $this->assignment_number,

            'title' => $this->title,

            'description' => $this->description,

            'priority' => $this->priority,

            'assignment_type' => $this->assignment_type,

            'location' => [

                'name' => $this->location_name,

                'address' => $this->address,

                'latitude' => $this->latitude,

                'longitude' => $this->longitude,

                'radius' => $this->radius,

                'polygon' => $this->polygon,

            ],

            'office' => [

                'id' => $this->office?->id,

                'code' => $this->office?->code,

                'name' => $this->office?->name,

            ],

            'schedule' => [

                'start_datetime' => optional($this->start_datetime)
                    ->format('Y-m-d H:i:s'),

                'end_datetime' => optional($this->end_datetime)
                    ->format('Y-m-d H:i:s'),

            ],

            'status' => $this->status,

            'employee_count' => $this->employee_count,

            'created_by' => [

                'id' => $this->creator?->id,

                'username' => $this->creator?->username,

                'full_name' => $this->creator?->employee?->full_name,

            ],

            'employees' => $this->whenLoaded(
                'employees',
                fn () => $this->employees->map(function ($employee) {

                    return [

                        'employee_id' => $employee->id,

                        'employee_number' => $employee->employee_number,

                        'full_name' => $employee->full_name,

                        'position' => $employee->currentEmployment?->position?->name,

                        'office' => $employee->currentEmployment?->office?->name,

                        'status' => $employee->pivot->status,

                        'assigned_at' => optional($employee->pivot->assigned_at)->format('Y-m-d H:i:s'),

                        'accepted_at' => optional($employee->pivot->accepted_at)->format('Y-m-d H:i:s'),

                        'started_at' => optional($employee->pivot->started_at)->format('Y-m-d H:i:s'),

                        'finished_at' => optional($employee->pivot->finished_at)->format('Y-m-d H:i:s'),

                    ];

                })
            ),

            /*
            |--------------------------------------------------------------------------
            | Status assignment ini khusus untuk employee yang sedang login
            | (dipakai Flutter untuk menampilkan tombol Accept / Reject /
            | Check In / Check Out / Complete yang sesuai).
            |--------------------------------------------------------------------------
            */

            'my_status' => $myPivot?->status,

            'my_actions' => $myPivot ? [

                'can_accept' => $myPivot->status === 'Assigned',

                'can_reject' => $myPivot->status === 'Assigned',

                'can_check_in' => $myPivot->status === 'Accepted' && !$hasAttendanceToday,

                'can_check_out' => $myPivot->status === 'In Progress',

                'can_complete' => $myPivot->status === 'In Progress'
                    || ($myPivot->status === 'Accepted' && $hasAttendanceToday),

            ] : null,

            'logs' => $this->whenLoaded(
                'logs',
                fn () => $this->logs->map(function ($log) {

                    return [

                        'action' => $log->action,

                        'description' => $log->description,

                        'user' => $log->user?->username,

                        'employee' => $log->employee?->full_name,

                        'created_at' => optional($log->created_at)->format('Y-m-d H:i:s'),

                    ];

                })
            ),

            'created_at' => optional($this->created_at)
                ->format('Y-m-d H:i:s'),

        ];
    }
}
