<?php

namespace App\Notifications;

use App\Models\Company;
use App\Notifications\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SubscriptionChanged extends Notification
{
    use Queueable;

    public function __construct(
        public Company $company,
        public string $oldPlan,
        public string $newPlan,
        public string $reason = 'updated'
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', FcmChannel::class];
    }

    private function title(): string
    {
        return match ($this->reason) {
            'expired' => 'Subscription Telah Berakhir',
            'cancelled' => 'Subscription Dibatalkan',
            'renewed' => 'Subscription Berhasil Diperpanjang',
            default => $this->newPlan === 'Free'
                ? 'Subscription Berubah ke Free'
                : 'Subscription Berhasil Diaktifkan',
        };
    }

    private function message(): string
    {
        return match ($this->reason) {
            'expired' => sprintf(
                '%s telah kembali ke Free karena masa %s berakhir. Data tetap tersimpan; limit karyawan mengikuti plan Free.',
                $this->company->name,
                $this->oldPlan,
            ),
            'cancelled' => sprintf('%s: %s dibatalkan dan sekarang menggunakan Free.', $this->company->name, $this->oldPlan),
            'renewed' => sprintf(
                '%s memperpanjang %s sampai %s.',
                $this->company->name,
                $this->newPlan,
                optional($this->company->subscription_end)?->translatedFormat('d M Y') ?? '-',
            ),
            default => sprintf('%s: %s → %s.', $this->company->name, $this->oldPlan, $this->newPlan),
        };
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'subscription_changed',
            'title' => $this->title(),
            'message' => $this->message(),
            'company_id' => $this->company->id,
            'old_plan' => $this->oldPlan,
            'new_plan' => $this->newPlan,
            'reason' => $this->reason,
            'subscription_end' => optional($this->company->subscription_end)?->toDateString(),
        ];
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => $this->title(),
            'body' => $this->message(),
            'data' => [
                'type' => 'subscription_changed',
                'company_id' => (string) $this->company->id,
                'new_plan' => $this->newPlan,
                'reason' => $this->reason,
            ],
        ];
    }
}
