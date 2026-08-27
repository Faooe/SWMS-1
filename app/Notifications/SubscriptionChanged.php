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
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', FcmChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'subscription_changed',
            'title' => $this->newPlan === 'Free' ? 'Subscription Berubah ke Free' : 'Subscription Berhasil Di-upgrade',
            'message' => sprintf('%s: %s → %s.', $this->company->name, $this->oldPlan, $this->newPlan),
            'company_id' => $this->company->id,
            'old_plan' => $this->oldPlan,
            'new_plan' => $this->newPlan,
            'reason' => $this->reason,
        ];
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => $this->newPlan === 'Free' ? 'Subscription Berubah ke Free' : 'Subscription Berhasil Di-upgrade',
            'body' => sprintf('%s sekarang menggunakan %s.', $this->company->name, $this->newPlan),
            'data' => [
                'type' => 'subscription_changed',
                'company_id' => $this->company->id,
                'new_plan' => $this->newPlan,
            ],
        ];
    }
}
