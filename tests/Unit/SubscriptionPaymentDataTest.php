<?php

namespace Tests\Unit;

use App\Support\SubscriptionPaymentData;
use Tests\TestCase;

class SubscriptionPaymentDataTest extends TestCase
{
    public function test_duration_options_and_labels_share_one_canonical_list(): void
    {
        $options = SubscriptionPaymentData::durationOptions();

        $this->assertSame([
            ['key' => '1_month', 'label' => '1 Bulan'],
            ['key' => '3_months', 'label' => '3 Bulan'],
            ['key' => '12_months', 'label' => '1 Tahun'],
        ], $options);
        $this->assertSame(['1_month', '3_months', '12_months'], SubscriptionPaymentData::durationKeys());
        $this->assertSame('3 Bulan', SubscriptionPaymentData::durationLabel('3_months'));
    }
}
