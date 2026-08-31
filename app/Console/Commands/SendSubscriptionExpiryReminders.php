<?php

namespace App\Console\Commands;

use App\Services\CompanyService;
use Illuminate\Console\Command;

class SendSubscriptionExpiryReminders extends Command
{
    protected $signature = 'subscriptions:send-expiry-reminders';

    protected $description = 'Send H-7/H-3/H-1 reminders for premium subscriptions that will expire.';

    public function handle(CompanyService $companyService): int
    {
        $count = $companyService->sendSubscriptionExpiryReminders();

        $this->info("Sent {$count} subscription expiry reminder(s).");

        return self::SUCCESS;
    }
}
