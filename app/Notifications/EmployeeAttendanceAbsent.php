<?php

namespace App\Notifications;

use App\Models\Attendance;
use App\Notifications\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EmployeeAttendanceAbsent extends Notification
{
    use Queueable;

    public function __construct(public Attendance $attendance) {}

    public function via(object $notifiable): array
    {
        return ['database', FcmChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'attendance_absent',
            'title' => 'Kamu Tercatat Tidak Hadir',
            'message' => sprintf('Kamu tercatat Absent pada %s. Buka Attendance untuk melihat detail.', optional($this->attendance->attendance_date)->format('d/m/Y') ?? '-'),
            'attendance_id' => $this->attendance->id,
        ];
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => 'Kamu Tercatat Tidak Hadir',
            'body' => sprintf('Status attendance %s tercatat Absent.', optional($this->attendance->attendance_date)->format('d/m/Y') ?? '-'),
            'data' => [
                'type' => 'attendance_absent',
                'attendance_id' => $this->attendance->id,
            ],
        ];
    }
}
