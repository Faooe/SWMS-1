<?php

namespace App\Notifications;

use App\Models\AssignmentEmployee;
use App\Notifications\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AssignmentReviewUpdated extends Notification
{
    use Queueable;

    public function __construct(
        protected AssignmentEmployee $assignmentEmployee,
        protected bool $approved
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
            'type' => $this->approved ? 'assignment_approved' : 'assignment_needs_revision',
            'title' => $this->approved ? 'Assignment Disetujui' : 'Assignment Perlu Direvisi',
            'message' => $this->approved
                ? sprintf('Hasil kerja "%s" sudah disetujui Company.', $assignment?->title ?? '-')
                : sprintf('Hasil kerja "%s" perlu direvisi. %s', $assignment?->title ?? '-', $this->assignmentEmployee->review_notes ?: ''),
            'assignment_id' => $assignment?->id,
            'assignment_uuid' => $assignment?->uuid,
            'assignment_employee_id' => $this->assignmentEmployee->id,
            'review_status' => $this->assignmentEmployee->review_status,
        ];
    }

    public function toFcm(object $notifiable): array
    {
        $assignment = $this->assignmentEmployee->assignment;

        return [
            'title' => $this->approved ? 'Assignment Disetujui' : 'Assignment Perlu Direvisi',
            'body' => $this->approved
                ? sprintf('Hasil kerja "%s" sudah disetujui.', $assignment?->title ?? '-')
                : sprintf('Hasil kerja "%s" perlu kamu revisi.', $assignment?->title ?? '-'),
            'data' => [
                'type' => $this->approved ? 'assignment_approved' : 'assignment_needs_revision',
                'assignment_id' => (string) ($assignment?->id ?? ''),
                'assignment_uuid' => (string) ($assignment?->uuid ?? ''),
                'assignment_employee_id' => (string) $this->assignmentEmployee->id,
            ],
        ];
    }
}
