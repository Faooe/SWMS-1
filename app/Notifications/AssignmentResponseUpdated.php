<?php

namespace App\Notifications;

use App\Models\AssignmentEmployee;
use App\Notifications\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AssignmentResponseUpdated extends Notification
{
    use Queueable;

    public function __construct(
        public AssignmentEmployee $assignmentEmployee,
        public bool $accepted
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', FcmChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        $assignment = $this->assignmentEmployee->assignment;
        $employee = $this->assignmentEmployee->employee;

        return [
            'type' => $this->accepted ? 'assignment_accepted' : 'assignment_rejected',
            'title' => $this->accepted ? 'Assignment Diterima' : 'Assignment Ditolak Employee',
            'message' => $this->accepted
                ? sprintf('%s menerima assignment "%s".', $employee?->full_name ?? 'Employee', $assignment?->title ?? '-')
                : sprintf('%s menolak assignment "%s". Alasan: %s', $employee?->full_name ?? 'Employee', $assignment?->title ?? '-', $this->assignmentEmployee->rejection_reason ?? '-'),
            'assignment_id' => $assignment?->id,
            'assignment_uuid' => $assignment?->uuid,
            'assignment_employee_id' => $this->assignmentEmployee->id,
        ];
    }

    public function toFcm(object $notifiable): array
    {
        $assignment = $this->assignmentEmployee->assignment;
        $employee = $this->assignmentEmployee->employee;

        return [
            'title' => $this->accepted ? 'Assignment Diterima' : 'Assignment Ditolak Employee',
            'body' => $this->accepted
                ? sprintf('%s menerima "%s".', $employee?->full_name ?? 'Employee', $assignment?->title ?? '-')
                : sprintf('%s menolak "%s". %s', $employee?->full_name ?? 'Employee', $assignment?->title ?? '-', $this->assignmentEmployee->rejection_reason ?? ''),
            'data' => [
                'type' => $this->accepted ? 'assignment_accepted' : 'assignment_rejected',
                'assignment_id' => $assignment?->id,
                'assignment_uuid' => $assignment?->uuid,
            ],
        ];
    }
}
