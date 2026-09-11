<?php

namespace Tests\Feature;

use Tests\TestCase;

class CompanyHrRecapRoutesTest extends TestCase
{
    public function test_company_recap_api_requires_authentication(): void
    {
        $this->getJson('/api/v1/company-recap?from=2026-07-01&to=2026-09-10')
            ->assertStatus(401)
            ->assertJsonPath('success', false);
    }

    public function test_company_recap_exports_require_authentication(): void
    {
        $this->getJson('/api/v1/company-recap/export/pdf')
            ->assertStatus(401);

        $this->getJson('/api/v1/company-recap/export/excel')
            ->assertStatus(401);
    }

    public function test_company_recap_web_page_redirects_guests_to_login(): void
    {
        $this->get('/company-recap')->assertRedirect(route('login'));
    }
}
