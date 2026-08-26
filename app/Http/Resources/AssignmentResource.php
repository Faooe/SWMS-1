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

        /*
        |--------------------------------------------------------------------------
        | Sudah check-out UNTUK ASSIGNMENT INI hari ini? Dipakai buat
        | 'can_check_out' di bawah -- Check Out baru boleh muncul SETELAH
        | foto bukti disubmit (completion_photo terisi) DAN belum pernah
        | check-out. Lihat catatan lengkap di App\Services\Attendance\
        | AttendanceService::checkOutAssignment() soal kenapa urutannya
        | "submit foto dulu baru boleh check out".
        |--------------------------------------------------------------------------
        */

        $assignmentCheckedOut = ($user && $user->employee)
            ? (bool) app(\App\Services\Attendance\AttendanceService::class)
                ->getTodayAssignmentAttendance($user->employee, $this->resource)
                ?->hasCheckedOut()
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

                        'completion_photo_url' => $employee->pivot->completion_photo
                            ? secure_file_url($employee->pivot->completion_photo)
                            : null,

                        'completion_photo_2_url' => $employee->pivot->completion_photo_2
                            ? secure_file_url($employee->pivot->completion_photo_2)
                            : null,

                        'completion_notes' => $employee->pivot->completion_notes,

                        'review_status' => $employee->pivot->review_status,

                        'review_notes' => $employee->pivot->review_notes,

                        'reviewed_at' => optional($employee->pivot->reviewed_at)->format('Y-m-d H:i:s'),

                        'revision_deadline_at' => optional($employee->pivot->revision_deadline_at)->format('Y-m-d H:i:s'),

                        'is_late_revision' => (bool) $employee->pivot->is_late_revision,

                        'revision_count' => (int) $employee->pivot->revision_count,

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

            'my_completion_photo_url' => $myPivot?->completion_photo
                ? secure_file_url($myPivot->completion_photo)
                : null,

            'my_completion_photo_2_url' => $myPivot?->completion_photo_2
                ? secure_file_url($myPivot->completion_photo_2)
                : null,

            'my_completion_notes' => $myPivot?->completion_notes,

            'my_review_status' => $myPivot?->review_status,

            'my_review_notes' => $myPivot?->review_notes,

            'my_revision_deadline_at' => optional($myPivot?->revision_deadline_at)->format('Y-m-d H:i:s'),

            'my_is_late_revision' => (bool) ($myPivot?->is_late_revision),

            'my_actions' => $myPivot ? [

                'can_accept' => $myPivot->status === 'Assigned',

                'can_reject' => $myPivot->status === 'Assigned',

                'can_check_in' => $myPivot->status === 'Accepted' && !$hasAttendanceToday,

                'can_check_out' => (bool) ($myPivot?->completion_photo) && !$assignmentCheckedOut,

                'can_complete' => ($myPivot->status === 'In Progress'
                    || ($myPivot->status === 'Accepted' && $hasAttendanceToday))
                    && $myPivot->review_status === null,

                'can_resubmit' => $myPivot->needsRevision() && !$myPivot->isPastRevisionGracePeriod(),

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
