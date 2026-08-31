<?php

namespace Tests\Unit;

use App\Support\SubscriptionPeriodCalculator;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;

class SubscriptionPeriodCalculatorTest extends TestCase
{
    public function test_same_active_plan_renews_from_existing_end_date(): void
    {
        $now = CarbonImmutable::parse('2026-08-31 10:00:00');
        $result = SubscriptionPeriodCalculator::calculate(
            'Premium Plus',
            CarbonImmutable::parse('2026-08-01'),
            CarbonImmutable::parse('2026-09-30'),
            'Premium Plus',
            '1_month',
            $now,
        );

        $this->assertTrue($result['is_renewal']);
        $this->assertSame('2026-08-01', $result['start']->toDateString());
        $this->assertSame('2026-10-30', $result['end']->toDateString());
    }

    public function test_plan_change_starts_immediately(): void
    {
        $now = CarbonImmutable::parse('2026-08-31 10:00:00');
        $result = SubscriptionPeriodCalculator::calculate(
            'Premium Plus',
            CarbonImmutable::parse('2026-08-01'),
            CarbonImmutable::parse('2026-09-30'),
            'Premium Max',
            '3_months',
            $now,
        );

        $this->assertFalse($result['is_renewal']);
        $this->assertSame('2026-08-31', $result['start']->toDateString());
        $this->assertSame('2026-11-30', $result['end']->toDateString());
    }

    public function test_expired_same_plan_starts_new_period_now(): void
    {
        $now = CarbonImmutable::parse('2026-08-31 10:00:00');
        $result = SubscriptionPeriodCalculator::calculate(
            'Premium Go',
            CarbonImmutable::parse('2026-06-01'),
            CarbonImmutable::parse('2026-08-30'),
            'Premium Go',
            '1_month',
            $now,
        );

        $this->assertFalse($result['is_renewal']);
        $this->assertSame('2026-09-30', $result['end']->toDateString());
    }
}
