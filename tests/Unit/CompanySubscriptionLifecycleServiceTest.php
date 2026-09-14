<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Services\CompanySubscriptionLifecycleService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CompanySubscriptionLifecycleServiceTest extends TestCase
{
    public function test_free_company_is_not_downgraded_again(): void
    {
        $company = new Company;
        $company->subscription_plan = 'Free';

        $this->assertSame(
            $company,
            (new CompanySubscriptionLifecycleService)->downgradeIfExpired($company)
        );
    }

    public function test_lifecycle_summary_counts_employees_against_plan_limit(): void
    {
        Schema::create('employees', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('employees')->insert([
            ['company_id' => 3],
            ['company_id' => 3],
        ]);

        $company = new Company;
        $company->id = 3;
        $company->subscription_plan = 'Free';
        $company->max_employee = 1;

        $summary = (new CompanySubscriptionLifecycleService)->subscriptionLifecycle($company);

        $this->assertSame(2, $summary['employee_count']);
        $this->assertSame(1, $summary['employee_limit']);
        $this->assertTrue($summary['over_employee_limit']);
        $this->assertFalse($summary['is_expired']);
    }
}
