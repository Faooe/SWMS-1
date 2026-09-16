<?php

namespace Tests\Unit;

use App\Services\CompanyAssignmentQuery;
use Tests\TestCase;

class CompanyAssignmentQueryTest extends TestCase
{
    public function test_list_filters_preserve_priority_date_and_sorting(): void
    {
        $query = (new CompanyAssignmentQuery)->build([
            'office' => 3,
            'priority' => 'High',
            'date' => '2026-09-14',
            'status' => 'Draft',
            'sort' => 'end_datetime',
            'direction' => 'asc',
        ]);

        $bindings = $query->getBindings();

        $this->assertContains(3, $bindings);
        $this->assertContains('High', $bindings);
        $this->assertSame(2, count(array_filter(
            $bindings,
            fn ($value) => $value === '2026-09-14'
        )));
        $this->assertContains('Draft', $bindings);
        $this->assertSame('end_datetime', $query->getQuery()->orders[0]['column']);
        $this->assertSame('asc', $query->getQuery()->orders[0]['direction']);
    }

    public function test_review_filter_targets_employee_review_status(): void
    {
        $query = (new CompanyAssignmentQuery)->build(['status' => 'Pending Review']);

        $this->assertContains('Pending Review', $query->getBindings());
        $this->assertStringContainsString('assignment_employees', $query->toSql());
    }

    public function test_not_started_filter_targets_active_unaccepted_employees(): void
    {
        $query = (new CompanyAssignmentQuery)->build(['status' => 'Belum Dikerjakan']);

        $this->assertContains('Assigned', $query->getBindings());
        $this->assertContains('Accepted', $query->getBindings());
        $this->assertStringContainsString('end_datetime', $query->toSql());
    }

    public function test_not_worked_filter_targets_review_or_overdue_workflow(): void
    {
        $query = (new CompanyAssignmentQuery)->build(['status' => 'Tidak Dikerjakan']);

        $this->assertContains('Not Worked', $query->getBindings());
        $this->assertContains('Expired', $query->getBindings());
        $this->assertStringContainsString('assignment_employees', $query->toSql());
    }
}
