<?php

namespace App\Notifications;

use App\Models\Company;
use App\Notifications\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SubscriptionExpiryReminder extends Notification
{
    use Queueable;

    public function __construct(
        public Company $company,
        public int $daysRemaining,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', FcmChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'subscription_expiry_reminder',
            'title' => "Subscription berakhir {$this->daysRemaining} hari lagi",
            'message' => sprintf(
                '%s (%s) akan berakhir pada %s. Perpanjang subscription agar fitur premium tetap aktif.',
                $this->company->name,
                $this->company->subscription_plan,
                optional($this->company->subscription_end)?->translatedFormat('d M Y') ?? '-'
            ),
            'company_id' => $this->company->id,
            'plan' => $this->company->subscription_plan,
            'days_remaining' => $this->daysRemaining,
            'subscription_end' => optional($this->company->subscription_end)?->toDateString(),
        ];
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => "Subscription {$this->daysRemaining} hari lagi",
            'body' => sprintf(
                '%s berakhir %s. Perpanjang agar fitur premium tetap aktif.',
                $this->company->subscription_plan,
                optional($this->company->subscription_end)?->translatedFormat('d M Y') ?? '-'
            ),
            'data' => [
                'type' => 'subscription_expiry_reminder',
                'company_id' => (string) $this->company->id,
                'days_remaining' => (string) $this->daysRemaining,
            ],
        ];
    }
}
