<?php

namespace Tests\Unit;

use App\Services\CompanyHrRecapSummaryBuilder;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CompanyHrRecapSummaryBuilderTest extends TestCase
{
    public function test_it_aggregates_attendance_and_assignment_totals(): void
    {
        $summary = app(CompanyHrRecapSummaryBuilder::class)->build(new Collection([
            $this->row(5, true, 4, 1, 0, 2, 1),
            $this->row(3, false, 2, 0, 0, 1, 1),
        ]));

        $this->assertSame(2, $summary['employees']);
        $this->assertSame(1, $summary['active_employees']);
        $this->assertSame(8, $summary['employee_working_days']);
        $this->assertSame(6, $summary['attended']);
        $this->assertSame(87.5, $summary['attendance_rate']);
        $this->assertSame(3, $summary['assignment_total']);
        $this->assertSame(2, $summary['assignment_completed']);
        $this->assertSame(66.7, $summary['completion_rate']);
    }

    public function test_empty_rows_return_zero_rates(): void
    {
        $summary = app(CompanyHrRecapSummaryBuilder::class)->build(collect());

        $this->assertSame(0, $summary['employees']);
        $this->assertSame(0.0, $summary['attendance_rate']);
        $this->assertSame(0.0, $summary['completion_rate']);
    }

    private function row(
        int $workingDays,
        bool $active,
        int $attended,
        int $leave,
        int $permission,
        int $assignmentTotal,
        int $assignmentCompleted,
    ): array {
        return [
            'working_days' => $workingDays,
            'is_active' => $active,
            'attended' => $attended,
            'leave' => $leave,
            'permission' => $permission,
            'assignment_total' => $assignmentTotal,
            'assignment_completed' => $assignmentCompleted,
            'attendance_records' => $attended,
            'present' => $attended,
            'late' => 0,
            'absent' => max(0, $workingDays - $attended - $leave - $permission),
            'assignment_in_progress' => 0,
            'assignment_rejected' => 0,
            'assignment_not_worked' => $assignmentTotal - $assignmentCompleted,
            'assignment_pending_review' => 0,
            'assignment_needs_revision' => 0,
        ];
    }
}
