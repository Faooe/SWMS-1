<?php

namespace App\Notifications;

use App\Models\AssignmentEmployee;
use App\Notifications\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AssignmentAssigned extends Notification
{
    use Queueable;

    public function __construct(protected AssignmentEmployee $assignmentEmployee)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', FcmChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        $assignment = $this->assignmentEmployee->assignment;

        return [
            'type' => 'assignment_assigned',
            'title' => 'Assignment Baru',
            'message' => sprintf(
                'Kamu mendapat assignment baru "%s". Jadwal: %s.',
                $assignment?->title ?? '-',
                optional($assignment?->start_datetime)->format('d/m/Y H:i') ?? '-'
            ),
            'assignment_id' => $assignment?->id,
            'assignment_uuid' => $assignment?->uuid,
            'assignment_employee_id' => $this->assignmentEmployee->id,
        ];
    }

    public function toFcm(object $notifiable): array
    {
        $assignment = $this->assignmentEmployee->assignment;

        return [
            'title' => 'Assignment Baru',
            'body' => sprintf('Kamu mendapat assignment baru "%s".', $assignment?->title ?? '-'),
            'data' => [
                'type' => 'assignment_assigned',
                'assignment_id' => (string) ($assignment?->id ?? ''),
                'assignment_uuid' => (string) ($assignment?->uuid ?? ''),
                'assignment_employee_id' => (string) $this->assignmentEmployee->id,
            ],
        ];
    }
}
