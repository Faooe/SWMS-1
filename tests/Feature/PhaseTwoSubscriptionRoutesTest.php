<?php

namespace Tests\Feature;

use Tests\TestCase;

class PhaseTwoSubscriptionRoutesTest extends TestCase
{
    public function test_company_billing_history_requires_authentication(): void
    {
        $this->getJson('/api/v1/subscription/history')
            ->assertStatus(401)
            ->assertJsonPath('success', false);
    }

    public function test_platform_billing_endpoint_requires_authentication(): void
    {
        $this->getJson('/api/v1/platform/premium/payments')
            ->assertStatus(401)
            ->assertJsonPath('success', false);
    }
}
