<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use App\Notifications\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeaveRequestReviewed extends Notification
{
    use Queueable;

    public function __construct(protected LeaveRequest $leaveRequest)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', FcmChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        $approved = $this->leaveRequest->status === 'Approved';
        $autoRejected = (bool) ($this->leaveRequest->auto_rejected_at ?? false);

        return [
            'type' => 'leave_request_reviewed',
            'title' => $approved ? 'Pengajuan Izin Disetujui' : ($autoRejected ? 'Pengajuan Izin Ditolak Otomatis' : 'Pengajuan Izin Ditolak'),
            'message' => $approved
                ? sprintf('Pengajuan %s kamu sudah disetujui.', $this->leaveRequest->type)
                : sprintf('Pengajuan %s kamu ditolak.%s', $this->leaveRequest->type, $this->leaveRequest->rejection_reason ? ' '.$this->leaveRequest->rejection_reason : ''),
            'leave_request_id' => $this->leaveRequest->id,
            'status' => $this->leaveRequest->status,
        ];
    }

    public function toFcm(object $notifiable): array
    {
        $approved = $this->leaveRequest->status === 'Approved';
        $autoRejected = (bool) ($this->leaveRequest->auto_rejected_at ?? false);

        return [
            'title' => $approved ? 'Pengajuan Izin Disetujui' : ($autoRejected ? 'Pengajuan Izin Ditolak Otomatis' : 'Pengajuan Izin Ditolak'),
            'body' => $approved
                ? sprintf('%s kamu sudah disetujui Company.', $this->leaveRequest->type)
                : sprintf('%s kamu tidak disetujui. Buka aplikasi untuk melihat detail.', $this->leaveRequest->type),
            'data' => [
                'type' => 'leave_request_reviewed',
                'leave_request_id' => (string) $this->leaveRequest->id,
                'status' => (string) $this->leaveRequest->status,
            ],
        ];
    }
}
