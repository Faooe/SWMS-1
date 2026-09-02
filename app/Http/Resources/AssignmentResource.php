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
        | Attendance umum hari ini masih dipakai untuk workflow assignment lama
        | (non-Daily Attendance). Untuk Daily Attendance, tombol Check In harus
        | ditentukan dari attendance assignment INI pada tanggal hari ini, bukan
        | dari Attendance Office atau assignment lain.
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

        $assignmentAttendance = ($user && $user->employee)
            ? app(\App\Services\Attendance\AttendanceService::class)
                ->getTodayAssignmentAttendance($user->employee, $this->resource)
            : null;

        $assignmentCheckedIn = (bool) $assignmentAttendance?->hasCheckedIn();
        $assignmentCheckedOut = (bool) $assignmentAttendance?->hasCheckedOut();

        // Tombol Check In hanya boleh muncul pada tanggal assignment dan setelah
        // jam mulai harian. Sebelumnya employee bisa check-in lebih awal pada
        // hari pertama / membuka detail assignment masa depan lewat UUID.
        $todayWithinAssignmentPeriod = $this->start_datetime && $this->end_datetime
            && today()->betweenIncluded(
                $this->start_datetime->copy()->startOfDay(),
                $this->end_datetime->copy()->startOfDay()
            );
        $todayCheckInStart = $this->start_datetime
            ? today()->setTimeFromTimeString($this->start_datetime->format('H:i:s'))
            : null;
        $todayCheckInEnd = $this->end_datetime
            ? today()->setTimeFromTimeString($this->end_datetime->format('H:i:s'))
            : null;
        $checkInWindowOpen = $todayWithinAssignmentPeriod
            && (!$todayCheckInStart || now()->greaterThanOrEqualTo($todayCheckInStart))
            && (!$todayCheckInEnd || now()->lessThanOrEqualTo($todayCheckInEnd));

        $dailyFinalDayReady = false;
        if ($user && $user->employee && $this->daily_attendance_enabled) {
            $finalDate = $this->end_datetime?->copy()->startOfDay()->toDateString();
            $finalRow = $finalDate
                ? collect($this->dailyAttendanceCalendar($user->employee))->firstWhere('date', $finalDate)
                : null;

            // Daily attendance adalah catatan kepatuhan harian, bukan pengunci
            // assignment selamanya. Pada hari terakhir cukup pastikan sesi hari
            // terakhir ditutup bila memang termasuk hari attendance wajib.
            // Hari sebelumnya yang Absent/Belum Check Out tetap tercatat di
            // kalender dan statistik, tetapi tidak membuat submit hasil buntu.
            $dailyFinalDayReady = $finalRow
                ? (!(bool) ($finalRow['required'] ?? false) || (bool) ($finalRow['checked_out'] ?? false))
                : true;
        }

        $checkoutCorrectionsByEmployee = collect();
        if ($this->relationLoaded('employees') && ($request->route('id') || $request->route('uuid') || $request->route('assignment'))) {
            $checkoutCorrectionsByEmployee = \App\Models\AttendanceCheckoutCorrection::query()
                ->where('assignment_id', $this->id)
                ->with('attendance')
                ->latest('id')
                ->get()
                ->groupBy('employee_id');
        }

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

            'daily_attendance_enabled' => (bool) $this->daily_attendance_enabled,
            'attendance_day_rule' => $this->attendance_day_rule ?? 'WORK_CALENDAR',

            'company_display_status' => $this->companyDisplayStatus(),
            'rejected_employee_count' => $this->rejectedEmployeeCount(),

            'employee_count' => $this->employee_count,

            'created_by' => [

                'id' => $this->creator?->id,

                'username' => $this->creator?->username,

                'full_name' => $this->creator?->employee?->full_name,

            ],

            'employees' => $this->whenLoaded(
                'employees',
                fn () => $this->employees->map(function ($employee) use ($checkoutCorrectionsByEmployee) {

                    return [

                        'employee_id' => $employee->id,

                        'employee_number' => $employee->employee_number,

                        'full_name' => $employee->full_name,
                        'photo_url' => $employee->photo
                            ? secure_file_url($employee->photo)
                            : null,

                        'position' => $employee->currentEmployment?->position?->name,

                        'office' => $employee->currentEmployment?->office?->name,

                        'status' => $employee->pivot->status,
                        'rejection_reason' => $employee->pivot->rejection_reason,

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

                        'checkout_corrections' => collect($checkoutCorrectionsByEmployee->get($employee->id, collect()))
                            ->map(fn ($correction) => $this->checkoutCorrectionPayload($correction))
                            ->values(),

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

            'attachments' => $this->whenLoaded('attachments', fn () => $this->attachments->map(fn ($file) => [
                'id' => $file->id,
                'name' => $file->original_name,
                'mime_type' => $file->mime_type,
                'size' => (int) $file->size,
                'url' => secure_file_url($file->file_path),
            ])->values()),

            'my_daily_attendance' => ($user && $user->employee && $this->daily_attendance_enabled)
                ? $this->dailyAttendanceCalendar($user->employee)
                : [],

            'my_daily_attendance_summary' => ($user && $user->employee && $this->daily_attendance_enabled)
                ? $this->dailyAttendanceSummary($user->employee)
                : null,

            'my_status' => $myPivot?->status,
            'my_rejection_reason' => $myPivot?->rejection_reason,

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

            'my_actions' => $myPivot ? (function () use ($myPivot, $hasAttendanceToday, $assignmentAttendance, $assignmentCheckedIn, $assignmentCheckedOut, $checkInWindowOpen, $dailyFinalDayReady) {
                // Deadline normal tetap menutup Accept/Reject/Check In tepat di
                // end_datetime. Daily Attendance punya grace khusus untuk
                // menyelesaikan Check Out + submit hasil pada hari terakhir
                // sampai batas check-out harian (23:00).
                $pastAssignmentDeadline = $this->end_datetime
                    && now()->greaterThanOrEqualTo($this->end_datetime);

                $completionDeadline = $this->end_datetime?->copy();
                if ($completionDeadline && $this->daily_attendance_enabled) {
                    $completionDeadline->setTime(23, 0, 0);
                }
                $pastCompletionDeadline = $completionDeadline
                    && now()->greaterThan($completionDeadline);

                $notWorked = in_array($myPivot->review_status, ['Not Worked', 'Expired'], true);
                $globalOperational = in_array($this->status, ['Assigned', 'In Progress'], true);
                $assignmentOpen = $globalOperational && !$pastAssignmentDeadline && !$notWorked;
                $completionOpen = $globalOperational && !$pastCompletionDeadline && !$notWorked;
                // Menutup attendance yang sudah dimulai tidak boleh hilang hanya
                // karena status global assignment sudah berubah. Yang benar-benar
                // menutup aksi Check Out adalah Cancelled/Draft, deadline harian,
                // atau status Not Worked/Expired employee.
                $attendanceCloseOpen = !in_array($this->status, ['Draft', 'Cancelled'], true)
                    && !$pastCompletionDeadline
                    && !$notWorked;

                return [
                    'can_accept' => $assignmentOpen
                        && $myPivot->status === 'Assigned'
                        && $myPivot->review_status === null,
                    'can_reject' => $assignmentOpen
                        && $myPivot->status === 'Assigned'
                        && $myPivot->review_status === null,
                    // Daily Attendance adalah attendance per-assignment/per-tanggal.
                    // Attendance Office (atau assignment lain) tidak boleh
                    // menyembunyikan tombol Check In untuk assignment ini.
                    'can_check_in' => $assignmentOpen
                        && $checkInWindowOpen
                        && ($myPivot->status === 'Accepted' || ($this->daily_attendance_enabled && $myPivot->status === 'In Progress'))
                        && ($this->daily_attendance_enabled
                            ? !$assignmentCheckedIn
                            : !$hasAttendanceToday),
                    // Check-out menutup attendance, bukan mengubah hasil review.
                    // Kalau company sangat cepat meng-approve hasil non-daily sebelum
                    // employee sempat check-out, tombol tetap harus tersedia selama
                    // attendance hari ini masih terbuka.
                    'can_check_out' => $attendanceCloseOpen
                        && ($this->daily_attendance_enabled || (bool) ($myPivot?->completion_photo))
                        && $assignmentAttendance !== null
                        && !$assignmentCheckedOut,
                    'can_complete' => $completionOpen
                        && (!$this->daily_attendance_enabled || (today()->isSameDay($this->end_datetime) && $dailyFinalDayReady))
                        && ($myPivot->status === 'In Progress'
                            || ($myPivot->status === 'Accepted' && $hasAttendanceToday))
                        && $myPivot->review_status === null,
                    'can_resubmit' => !in_array($this->status, ['Draft', 'Cancelled'], true)
                        && $myPivot->needsRevision()
                        && !$myPivot->isPastRevisionGracePeriod(),
                ];
            })() : null,

            'logs' => $this->whenLoaded(
                'logs',
                function () use ($user) {
                    $logs = $this->logs;

                    // Employee hanya melihat event umum assignment + event miliknya
                    // sendiri. Company Admin tetap melihat timeline lengkap semua employee.
                    if ($user?->role?->code === 'EMPLOYEE' && $user->employee_id) {
                        $logs = $logs->filter(fn ($log) =>
                            $log->employee_id === null || (int) $log->employee_id === (int) $user->employee_id
                        );
                    }

                    return $logs->map(function ($log) {

                    return [

                        'action' => $log->action,

                        'description' => $log->description,

                        'user' => $log->user?->username,

                        'employee' => $log->employee?->full_name,

                        // API contract: properties selalu JSON object.
                        // Sebelumnya null menjadi [] sehingga parser Flutter
                        // menganggap List dan crash pada My Assignment.
                        'properties' => (object) ($log->properties ?? []),

                        'created_at' => optional($log->created_at)->format('Y-m-d H:i:s'),

                    ];

                    });
                }
            ),

            'created_at' => optional($this->created_at)
                ->format('Y-m-d H:i:s'),

        ];
    }

    private function dailyAttendanceCalendar(\App\Models\Employee $employee): array
    {
        $calendar = app(\App\Services\Attendance\WorkCalendarService::class);
        $records = \App\Models\Attendance::query()
            ->where('employee_id', $employee->id)
            ->where('assignment_id', $this->id)
            ->where('attendance_type', 'ASSIGNMENT')
            ->whereBetween('attendance_date', [$this->start_datetime->copy()->startOfDay(), $this->end_datetime->copy()->endOfDay()])
            ->get()->keyBy(fn ($a) => $a->attendance_date->toDateString());

        $corrections = \App\Models\AttendanceCheckoutCorrection::query()
            ->whereIn('attendance_id', $records->pluck('id')->filter()->values())
            ->with('attendance')
            ->latest('id')
            ->get()
            ->groupBy('attendance_id');

        $rows = [];
        $cursor = $this->start_datetime->copy()->startOfDay();
        $last = $this->end_datetime->copy()->startOfDay();
        while ($cursor->lte($last)) {
            $date = $cursor->toDateString();
            $required = $this->attendance_day_rule === 'EVERY_DAY' || $calendar->isWorkingDay($employee->company, $cursor);
            $attendance = $records->get($date);
            $isPast = $cursor->lt(today());
            $isToday = $cursor->isSameDay(today());
            $status = 'UPCOMING';
            if (!$required) $status = 'OFF';
            elseif ($attendance?->is_checked_out) $status = ($attendance->attendance_status === 'Late' ? 'LATE' : 'PRESENT');
            elseif ($attendance?->is_checked_in) $status = $isPast ? 'INCOMPLETE' : 'WORKING';
            elseif ($isPast) $status = 'ABSENT';
            elseif ($isToday) $status = 'TODAY';

            $rows[] = [
                'date' => $date, 'required' => $required, 'status' => $status,
                'attendance_status' => $attendance?->attendance_status,
                'check_in' => optional($attendance?->check_in_time)->format('H:i'),
                'check_out' => optional($attendance?->check_out_time)->format('H:i'),
                'checked_in' => (bool) ($attendance?->is_checked_in),
                'checked_out' => (bool) ($attendance?->is_checked_out),
                'late_minutes' => (int) ($attendance?->late_minutes ?? 0),
                'work_minutes' => (int) ($attendance?->work_minutes ?? 0),
                'early_leave_minutes' => (int) ($attendance?->early_leave_minutes ?? 0),
                'overtime_minutes' => (int) ($attendance?->overtime_minutes ?? 0),
                'checkout_correction' => $attendance
                    ? optional($corrections->get($attendance->id)?->first(), fn ($correction) => $this->checkoutCorrectionPayload($correction))
                    : null,
            ];
            $cursor->addDay();
        }
        return $rows;
    }

    private function hasCompletedRequiredDailyAttendance(\App\Models\Employee $employee): bool
    {
        $rows = collect($this->dailyAttendanceCalendar($employee))->where('required', true);

        // Assignment tanpa hari wajib tidak perlu memaksa Check Out yang tidak
        // mungkin dilakukan (mis. seluruh rentang adalah hari libur).
        return $rows->isEmpty() || $rows->every(fn ($row) => (bool) ($row['checked_out'] ?? false));
    }

    private function checkoutCorrectionPayload(\App\Models\AttendanceCheckoutCorrection $correction): array
    {
        return [
            'id' => $correction->id,
            'uuid' => $correction->uuid,
            'attendance_id' => $correction->attendance_id,
            'employee_id' => $correction->employee_id,
            'attendance_date' => optional($correction->attendance?->attendance_date)->toDateString(),
            'requested_check_out_time' => substr((string) $correction->requested_check_out_time, 0, 5),
            'reason' => $correction->reason,
            'status' => $correction->status,
            'review_notes' => $correction->review_notes,
            'reviewed_at' => optional($correction->reviewed_at)->format('Y-m-d H:i:s'),
            'created_at' => optional($correction->created_at)->format('Y-m-d H:i:s'),
        ];
    }

    private function dailyAttendanceSummary(\App\Models\Employee $employee): array
    {
        $rows = collect($this->dailyAttendanceCalendar($employee));
        $required = $rows->where('required', true);
        // Kehadiran dihitung sejak employee berhasil Check In.
        // "Selesai" tetap berarti attendance hari tersebut sudah Check Out.
        $attended = $required->where('checked_in', true)->count();
        $completed = $required->where('checked_out', true)->count();
        $total = $required->count();
        return [
            'required_days' => $total,
            'attended_days' => $attended,
            'completed_days' => $completed,
            'absent_days' => $required->where('status', 'ABSENT')->count(),
            'incomplete_days' => $required->where('status', 'INCOMPLETE')->count(),
            'late_days' => $required->where('status', 'LATE')->count(),
            'attendance_rate' => $total > 0 ? round(($attended / $total) * 100, 1) : 0,
            'work_minutes' => (int) $rows->sum('work_minutes'),
            'early_leave_minutes' => (int) $rows->sum('early_leave_minutes'),
            'overtime_minutes' => (int) $rows->sum('overtime_minutes'),
        ];
    }
}
