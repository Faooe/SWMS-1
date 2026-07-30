<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use App\Notifications\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class LeaveRequestSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected LeaveRequest $leaveRequest
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Channel yang Dipakai
    |--------------------------------------------------------------------------
    | 'database' -> muncul di bell/badge dashboard web admin.
    |
    | FcmChannel (push notification ke HP) SENGAJA belum diaktifkan --
    | aplikasi mobile (Flutter) belum dibuat, jadi belum ada yang bisa
    | menerima push-nya. Nanti kalau Flutter app + Firebase sudah siap,
    | tinggal aktifkan lagi baris FcmChannel::class di bawah dan
    | `composer require kreait/laravel-firebase`. Method toFcm() di
    | bawah tidak perlu diubah, sudah siap dipakai.
    */

    public function via(object $notifiable): array
    {
        return ['database'];

        // return ['database', FcmChannel::class];
    }

    /*
    |--------------------------------------------------------------------------
    | Data untuk Bell/Badge (Database Channel)
    |--------------------------------------------------------------------------
    */

    public function toArray(object $notifiable): array
    {
        return [

            'type' => 'leave_request_submitted',

            'title' => 'Pengajuan Izin Baru',

            'message' => sprintf(
                '%s mengajukan izin %s (%s s/d %s).',
                $this->leaveRequest->employee->full_name,
                $this->leaveRequest->type,
                $this->leaveRequest->start_date->format('d M Y'),
                $this->leaveRequest->end_date->format('d M Y'),
            ),

            'leave_request_id' => $this->leaveRequest->id,

            'employee_id' => $this->leaveRequest->employee_id,

            'employee_name' => $this->leaveRequest->employee->full_name,

            'url' => route('leaves.index'),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Data untuk Push Notification (FCM Channel)
    |--------------------------------------------------------------------------
    */

    public function toFcm(object $notifiable): array
    {
        return [

            'title' => 'Pengajuan Izin Baru',

            'body' => sprintf(
                '%s mengajukan izin %s.',
                $this->leaveRequest->employee->full_name,
                $this->leaveRequest->type,
            ),

            'data' => [
                'type' => 'leave_request_submitted',
                'leave_request_id' => (string) $this->leaveRequest->id,
            ],

        ];
    }
}