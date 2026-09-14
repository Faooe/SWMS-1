<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Services\Employee\EmploymentReferenceValidator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class EmploymentReferenceValidatorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        foreach (['departments', 'positions', 'offices', 'employees'] as $table) {
            Schema::create($table, function (Blueprint $blueprint): void {
                $blueprint->id();
                $blueprint->unsignedBigInteger('company_id');
                $blueprint->boolean('is_active')->default(true);
                $blueprint->softDeletes();
            });
        }

        Schema::create('teams', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->unsignedBigInteger('company_id');
            $blueprint->unsignedBigInteger('department_id');
            $blueprint->softDeletes();
        });

        DB::table('departments')->insert([
            ['id' => 1, 'company_id' => 10],
            ['id' => 2, 'company_id' => 20],
            ['id' => 3, 'company_id' => 10],
        ]);
        DB::table('positions')->insert([
            ['id' => 1, 'company_id' => 10],
            ['id' => 2, 'company_id' => 20],
        ]);
        DB::table('offices')->insert([
            ['id' => 1, 'company_id' => 10],
            ['id' => 2, 'company_id' => 20],
        ]);
        DB::table('teams')->insert([
            ['id' => 1, 'company_id' => 10, 'department_id' => 1],
            ['id' => 2, 'company_id' => 10, 'department_id' => 3],
        ]);
        DB::table('employees')->insert([
            ['id' => 1, 'company_id' => 10, 'is_active' => true],
            ['id' => 2, 'company_id' => 20, 'is_active' => true],
        ]);
    }

    public function test_matching_company_references_are_accepted(): void
    {
        $this->expectNotToPerformAssertions();

        app(EmploymentReferenceValidator::class)->assertValid([
            'company_id' => 10,
            'department_id' => 1,
            'position_id' => 1,
            'office_id' => 1,
            'team_id' => 1,
            'supervisor_id' => 1,
        ]);
    }

    public function test_foreign_company_references_are_rejected(): void
    {
        try {
            app(EmploymentReferenceValidator::class)->assertValid([
                'company_id' => 10,
                'department_id' => 2,
                'position_id' => 2,
                'office_id' => 2,
                'supervisor_id' => 2,
            ]);
            $this->fail('References from another company must be rejected.');
        } catch (ValidationException $exception) {
            $this->assertEqualsCanonicalizing(
                ['department_id', 'position_id', 'office_id', 'supervisor_id'],
                array_keys($exception->errors())
            );
        }
    }

    public function test_team_must_belong_to_selected_department(): void
    {
        $this->expectException(ValidationException::class);

        app(EmploymentReferenceValidator::class)->assertValid([
            'company_id' => 10,
            'department_id' => 1,
            'position_id' => 1,
            'team_id' => 2,
        ]);
    }

    public function test_employee_cannot_supervise_themselves(): void
    {
        $employee = new Employee;
        $employee->id = 1;
        $employee->company_id = 10;

        try {
            app(EmploymentReferenceValidator::class)->assertValid([
                'department_id' => 1,
                'position_id' => 1,
                'supervisor_id' => 1,
            ], $employee);
            $this->fail('Self-supervision must be rejected.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('supervisor_id', $exception->errors());
        }
    }
}
