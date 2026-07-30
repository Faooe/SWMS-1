<?php

namespace App\Notifications;

use App\Models\Attendance;
use App\Notifications\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class EmployeeMarkedAbsent extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Attendance $attendance
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Channel yang Dipakai
    |--------------------------------------------------------------------------
    | FcmChannel (push HP) sengaja dimatikan dulu -- lihat catatan yang
    | sama di App\Notifications\LeaveRequestSubmitted.
    */

    public function via(object $notifiable): array
    {
        return ['database'];

        // return ['database', FcmChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        return [

            'type' => 'employee_marked_absent',

            'title' => 'Karyawan Tidak Hadir',

            'message' => sprintf(
                '%s tidak hadir (Absent) pada %s.',
                $this->attendance->employee->full_name,
                $this->attendance->attendance_date->format('d M Y'),
            ),

            'attendance_id' => $this->attendance->id,

            'employee_id' => $this->attendance->employee_id,

            'employee_name' => $this->attendance->employee->full_name,

            'url' => route('attendance.index'),

        ];
    }

    public function toFcm(object $notifiable): array
    {
        return [

            'title' => 'Karyawan Tidak Hadir',

            'body' => sprintf(
                '%s tidak hadir (Absent) hari ini.',
                $this->attendance->employee->full_name,
            ),

            'data' => [
                'type' => 'employee_marked_absent',
                'attendance_id' => (string) $this->attendance->id,
            ],

        ];
    }
}