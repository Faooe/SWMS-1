<?php

namespace App\Notifications;

use App\Models\AssignmentEmployee;
use App\Notifications\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AssignmentNotWorked extends Notification
{
    use Queueable;

    public function __construct(
        protected AssignmentEmployee $assignmentEmployee,
        protected bool $revisionExpired = false,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', FcmChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        $assignment = $this->assignmentEmployee->assignment;
        return [
            'type' => 'assignment_not_worked',
            'title' => 'Assignment Tidak Dikerjakan',
            'message' => $this->revisionExpired
                ? sprintf('Batas revisi assignment "%s" sudah lewat tanpa submit ulang.', $assignment?->title ?? '-')
                : sprintf('Batas waktu assignment "%s" sudah lewat tanpa penyelesaian.', $assignment?->title ?? '-'),
            'assignment_id' => $assignment?->id,
            'assignment_uuid' => $assignment?->uuid,
            'assignment_employee_id' => $this->assignmentEmployee->id,
            'review_status' => 'Not Worked',
        ];
    }

    public function toFcm(object $notifiable): array
    {
        $assignment = $this->assignmentEmployee->assignment;
        return [
            'title' => 'Assignment Tidak Dikerjakan',
            'body' => $this->revisionExpired
                ? sprintf('Batas revisi "%s" sudah lewat.', $assignment?->title ?? '-')
                : sprintf('Batas waktu "%s" sudah lewat.', $assignment?->title ?? '-'),
            'data' => [
                'type' => 'assignment_not_worked',
                'assignment_id' => (string) ($assignment?->id ?? ''),
                'assignment_uuid' => (string) ($assignment?->uuid ?? ''),
            ],
        ];
    }
}
