<?php

namespace App\Console\Commands;

use App\Services\CompanyService;
use Illuminate\Console\Command;

class DowngradeExpiredSubscriptions extends Command
{
    protected $signature = 'subscriptions:downgrade-expired';

    protected $description = 'Downgrade companies with expired premium subscription back to Free plan.';

    public function handle(CompanyService $companyService): int
    {

        $count = $companyService->downgradeExpiredSubscriptions();

        $this->info("Downgraded {$count} expired subscription(s) to Free.");

        return self::SUCCESS;

    }
}