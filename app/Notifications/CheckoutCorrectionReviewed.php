<?php

namespace App\Notifications;

use App\Models\AttendanceCheckoutCorrection;
use App\Notifications\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CheckoutCorrectionReviewed extends Notification
{
    use Queueable;
    public function __construct(protected AttendanceCheckoutCorrection $correction) {}
    public function via(object $notifiable): array { return ['database', FcmChannel::class]; }
    public function toArray(object $notifiable): array
    {
        $c = $this->correction->loadMissing('assignment');
        $approved = $c->status === 'Approved';
        return [
            'type' => $approved ? 'checkout_correction_approved' : 'checkout_correction_rejected',
            'title' => $approved ? 'Koreksi Check Out Disetujui' : 'Koreksi Check Out Ditolak',
            'message' => $approved ? sprintf('Koreksi Check Out "%s" disetujui.', $c->assignment?->title ?? '-') : sprintf('Koreksi Check Out "%s" ditolak. %s', $c->assignment?->title ?? '-', $c->review_notes ?: ''),
            'assignment_id' => $c->assignment_id,
            'assignment_uuid' => $c->assignment?->uuid,
            'correction_id' => $c->id,
        ];
    }
    public function toFcm(object $notifiable): array
    {
        $c = $this->correction->loadMissing('assignment'); $approved = $c->status === 'Approved';
        return ['title'=>$approved?'Koreksi Check Out Disetujui':'Koreksi Check Out Ditolak','body'=>sprintf('Pengajuan koreksi untuk "%s" %s.', $c->assignment?->title ?? '-', $approved?'disetujui':'ditolak'),'data'=>['type'=>$approved?'checkout_correction_approved':'checkout_correction_rejected','assignment_uuid'=>(string)($c->assignment?->uuid ?? ''),'correction_id'=>(string)$c->id]];
    }
}
