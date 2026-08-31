<?php

namespace Tests\Feature;

use Tests\TestCase;

class PhaseOneApiHardeningTest extends TestCase
{
    public function test_api_unauthenticated_response_is_json_and_has_request_id(): void
    {
        $response = $this->getJson('/api/v1/me', ['X-Request-ID' => 'test-request-123']);

        $response->assertStatus(401)
            ->assertJsonPath('success', false)
            ->assertJsonPath('request_id', 'test-request-123')
            ->assertHeader('X-Request-ID', 'test-request-123');
    }

    public function test_midtrans_connectivity_probe_is_acknowledged(): void
    {
        $response = $this->getJson('/api/v1/subscription/callback', ['X-Request-ID' => 'midtrans-probe']);

        $response->assertOk()
            ->assertJsonPath('message', 'OK')
            ->assertHeader('X-Request-ID', 'midtrans-probe');
    }

    public function test_midtrans_malformed_post_is_acknowledged_without_processing(): void
    {
        $response = $this->postJson('/api/v1/subscription/callback', [
            'order_id' => 'payment_notif_test_dummy',
        ]);

        $response->assertOk()
            ->assertJsonPath('processed', false);
    }

    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/up');

        $response->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }
}
