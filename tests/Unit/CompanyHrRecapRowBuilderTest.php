<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Services\CompanyHrRecapRowBuilder;
use Tests\TestCase;

class CompanyHrRecapRowBuilderTest extends TestCase
{
    public function test_it_calculates_missing_days_and_weighted_performance(): void
    {
        $employee = $this->employee();

        $row = app(CompanyHrRecapRowBuilder::class)->build(
            $employee,
            5,
            (object) [
                'records' => 4,
                'present' => 2,
                'late' => 1,
                'leave_count' => 1,
                'permission_count' => 0,
                'absent' => 0,
                'work_minutes' => 960,
            ],
            (object) [
                'total' => 2,
                'completed' => 1,
                'not_worked' => 1,
            ],
        );

        $this->assertSame(3, $row['attended']);
        $this->assertSame(1, $row['absent']);
        $this->assertSame(80.0, $row['attendance_rate']);
        $this->assertSame(50.0, $row['completion_rate']);
        $this->assertSame(68.0, $row['performance_score']);
        $this->assertSame(1, $row['assignment_not_worked']);
        $this->assertSame(960, $row['work_minutes']);
    }

    public function test_empty_period_does_not_divide_by_zero(): void
    {
        $row = app(CompanyHrRecapRowBuilder::class)->build($this->employee(), 0, null, null);

        $this->assertSame(0, $row['absent']);
        $this->assertSame(0.0, $row['attendance_rate']);
        $this->assertSame(0.0, $row['completion_rate']);
        $this->assertSame(0.0, $row['performance_score']);
        $this->assertSame('-', $row['department']);
    }

    private function employee(): Employee
    {
        $employee = new Employee;
        $employee->id = 7;
        $employee->employee_number = 'NF-007';
        $employee->full_name = 'Employee Test';
        $employee->is_active = true;
        $employee->setRelation('currentEmployment', null);

        return $employee;
    }
}
