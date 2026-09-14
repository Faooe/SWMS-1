<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Models\Office;
use App\Services\Attendance\AttendanceLookupService;
use Tests\TestCase;

class AttendanceLookupServiceTest extends TestCase
{
    public function test_it_resolves_an_employee_current_office(): void
    {
        $employee = new Employee;
        $office = new Office;
        $office->id = 12;
        $office->name = 'Head Office';

        $employment = new \stdClass;
        $employment->office = $office;
        $employee->setRelation('currentEmployment', $employment);

        $this->assertSame($office, app(AttendanceLookupService::class)->office($employee));
    }
}
