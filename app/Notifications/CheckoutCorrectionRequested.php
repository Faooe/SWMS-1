<?php

namespace App\Notifications;

use App\Models\AttendanceCheckoutCorrection;
use App\Notifications\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CheckoutCorrectionRequested extends Notification
{
    use Queueable;
    public function __construct(protected AttendanceCheckoutCorrection $correction) {}
    public function via(object $notifiable): array { return ['database', FcmChannel::class]; }
    public function toArray(object $notifiable): array
    {
        $c = $this->correction->loadMissing(['assignment','employee','attendance']);
        return [
            'type' => 'checkout_correction_requested',
            'title' => 'Koreksi Check Out',
            'message' => sprintf('%s mengajukan koreksi Check Out %s untuk "%s".', $c->employee?->full_name ?? 'Employee', substr((string)$c->requested_check_out_time,0,5), $c->assignment?->title ?? '-'),
            'assignment_id' => $c->assignment_id,
            'employee_id' => $c->employee_id,
            'correction_id' => $c->id,
            'url' => route('assignments.show', $c->assignment_id),
        ];
    }
    public function toFcm(object $notifiable): array
    {
        $c = $this->correction->loadMissing(['assignment','employee']);
        return ['title'=>'Koreksi Check Out','body'=>sprintf('%s meminta koreksi Check Out untuk "%s".', $c->employee?->full_name ?? 'Employee', $c->assignment?->title ?? '-'),'data'=>['type'=>'checkout_correction_requested','assignment_id'=>(string)$c->assignment_id,'correction_id'=>(string)$c->id]];
    }
}
