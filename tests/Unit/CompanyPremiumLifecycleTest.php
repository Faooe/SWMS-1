<?php

namespace Tests\Unit;

use App\Models\Company;
use Carbon\Carbon;
use Tests\TestCase;

class CompanyPremiumLifecycleTest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }
    public function test_expired_premium_is_not_treated_as_active_even_before_cron_downgrade(): void
    {
        Carbon::setTestNow('2026-08-31 12:00:00');

        $company = new Company();
        $company->forceFill([
            'subscription_plan' => 'Premium Plus',
            'subscription_end' => '2026-08-30',
        ]);

        $this->assertFalse($company->isPremium());

    }

    public function test_premium_remains_active_through_its_end_date(): void
    {
        Carbon::setTestNow('2026-08-31 12:00:00');

        $company = new Company();
        $company->forceFill([
            'subscription_plan' => 'Premium Max',
            'subscription_end' => '2026-08-31',
        ]);

        $this->assertTrue($company->isPremium());

    }
}
