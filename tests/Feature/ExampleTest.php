<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_health_endpoint_returns_a_successful_response(): void
    {
        // Root SWMS dapat redirect ke login/dashboard; /up adalah health endpoint
        // publik yang memang harus memberikan HTTP 200.
        $response = $this->get('/up');

        $response->assertOk();
    }
}
