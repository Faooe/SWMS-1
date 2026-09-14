<?php

namespace Tests\Unit;

use App\Models\Assignment;
use App\Services\EmployeeAssignmentOrdering;
use App\Services\EmployeeAssignmentQuery;
use App\Services\EmployeeAssignmentStatistics;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EmployeeAssignmentOrderingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Dedicated in-memory connection: never migrate or modify application data.
        config(['database.default' => 'ordering_test', 'database.connections.ordering_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
        ]]);
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('assignment_number')->nullable();
            $table->string('title')->nullable();
            $table->string('location_name')->nullable();
            $table->string('status');
            $table->string('priority');
            $table->dateTime('start_datetime')->nullable();
            $table->dateTime('end_datetime')->nullable();
            $table->dateTime('created_at');
            $table->softDeletes();
        });
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
        });
        Schema::create('assignment_employees', function (Blueprint $table) {
            $table->integer('assignment_id');
            $table->integer('employee_id');
            $table->string('status');
            $table->string('review_status')->nullable();
            $table->boolean('is_late_revision')->default(false);
        });
        DB::table('employees')->insert([['id' => 7], ['id' => 99]]);
    }

    protected function tearDown(): void
    {
        DB::purge('ordering_test');
        parent::tearDown();
    }

    private function assignment(int $id, string $priority = 'High', string $status = 'Assigned', ?string $review = null, ?string $deadline = '2026-09-20 17:00:00', string $created = '2026-09-10 08:00:00', string $global = 'Assigned', string $start = '2026-09-10 08:00:00'): void
    {
        DB::table('assignments')->insert([
            'id' => $id, 'priority' => $priority, 'status' => $global,
            'start_datetime' => $start,
            'end_datetime' => $deadline, 'created_at' => $created,
        ]);
        DB::table('assignment_employees')->insert([
            'assignment_id' => $id, 'employee_id' => 7,
            'status' => $status, 'review_status' => $review,
            'is_late_revision' => false,
        ]);
    }

    private function ids(): array
    {
        return EmployeeAssignmentOrdering::apply(Assignment::query(), 7)->get()->modelKeys();
    }

    public function test_employee_workflow_precedes_priority_and_ignores_other_employees(): void
    {
        $this->assignment(1, 'Critical', 'Completed', 'Approved');
        $this->assignment(2, 'Critical', 'Completed', 'Pending Review');
        $this->assignment(3, 'Low');
        $this->assignment(4, 'High', 'Completed', 'Needs Revision');
        $this->assignment(5, 'Critical', 'Assigned', 'Expired');
        $this->assignment(6, 'Critical', global: 'Cancelled');
        DB::table('assignment_employees')->insert([
            'assignment_id' => 1, 'employee_id' => 99, 'status' => 'Assigned', 'review_status' => null,
        ]);
        $this->assertSame([4, 3, 2, 1, 6, 5], $this->ids());
    }

    public function test_priority_then_deadline_then_newest_and_stable_pagination(): void
    {
        $this->assignment(1, 'Low');
        $this->assignment(2, 'Medium');
        $this->assignment(3, 'High');
        $this->assignment(4, 'Critical');
        $this->assignment(5, 'High', deadline: '2026-09-11 17:00:00');
        $this->assignment(6, 'High', created: '2026-09-10 09:00:00');
        $this->assignment(7, 'High', deadline: null);
        $this->assignment(8, 'High', created: '2026-09-10 09:00:00');
        $expected = [4, 5, 8, 6, 3, 7, 2, 1];
        $this->assertSame($expected, $this->ids());
        $page = EmployeeAssignmentOrdering::apply(Assignment::query(), 7)->paginate(3, ['*'], 'page', 2);
        $this->assertSame([6, 3, 7], $page->getCollection()->modelKeys());
        $this->assertSame(8, $page->total());
        $this->assertSame([4], EmployeeAssignmentOrdering::apply(Assignment::where('priority', 'Critical'), 7)->get()->modelKeys());
    }

    public function test_processed_categories_ignore_priority_and_sort_by_schedule_newest_first(): void
    {
        foreach (['Pending Review', 'Approved', 'Expired'] as $index => $review) {
            $base = $index * 10;
            $this->assignment($base + 1, 'Critical', 'Completed', $review, deadline: '2026-09-19 17:00:00');
            $this->assignment($base + 2, 'Low', 'Completed', $review, deadline: '2026-09-20 09:00:00');
            $this->assignment($base + 3, 'Medium', 'Completed', $review, deadline: '2026-09-20 10:00:00');
            $this->assignment($base + 4, 'Critical', 'Completed', $review, deadline: null);
        }
        $this->assertSame([3, 2, 1, 4, 13, 12, 11, 14, 23, 22, 21, 24], $this->ids());
    }

    public function test_active_work_uses_earliest_time_but_completed_uses_latest_time(): void
    {
        $this->assignment(1, start: '2026-09-10 09:00:00');
        $this->assignment(2, start: '2026-09-10 08:00:00');
        $this->assignment(3, status: 'Completed', review: 'Approved', start: '2026-09-10 08:00:00');
        $this->assignment(4, 'Low', 'Completed', 'Approved', start: '2026-09-10 09:00:00');
        $this->assertSame([2, 1, 4, 3], $this->ids());
    }

    public function test_employee_assignment_filters_follow_employee_workflow(): void
    {
        $this->assignment(1, status: 'Assigned');
        $this->assignment(2, status: 'Accepted');
        $this->assignment(3, status: 'In Progress');
        $this->assignment(4, status: 'Completed', review: 'Pending Review');
        $this->assignment(5, status: 'Completed', review: 'Approved');
        $this->assignment(6, status: 'Rejected');

        $filters = new EmployeeAssignmentQuery;
        $baseQuery = fn () => Assignment::query()->whereHas(
            'employees',
            fn ($query) => $query->where('employees.id', 7)
        );

        $assigned = $filters->applyFilters($baseQuery(), 7, ['status' => 'Assigned'])
            ->orderBy('id')
            ->pluck('id')
            ->all();
        $completed = $filters->applyFilters($baseQuery(), 7, ['status' => 'Completed'])
            ->pluck('id')
            ->all();
        $cancelled = $filters->applyFilters($baseQuery(), 7, ['status' => 'Cancelled'])
            ->pluck('id')
            ->all();

        $this->assertSame([1, 2, 3], $assigned);
        $this->assertSame([5], $completed);
        $this->assertSame([6], $cancelled);
    }

    public function test_priority_and_date_filters_can_be_combined(): void
    {
        $this->assignment(1, 'Critical', start: '2026-09-09 08:00:00', deadline: '2026-09-12 17:00:00');
        $this->assignment(2, 'Critical', start: '2026-09-12 08:00:00', deadline: '2026-09-13 17:00:00');
        $this->assignment(3, 'Low', start: '2026-09-09 08:00:00', deadline: '2026-09-12 17:00:00');

        $query = Assignment::query()->whereHas(
            'employees',
            fn ($employeeQuery) => $employeeQuery->where('employees.id', 7)
        );

        $ids = (new EmployeeAssignmentQuery)
            ->applyFilters($query, 7, ['priority' => 'Critical', 'date' => '2026-09-10'])
            ->pluck('id')
            ->all();

        $this->assertSame([1], $ids);
    }

    public function test_statistics_use_the_same_employee_workflow_definitions(): void
    {
        $this->assignment(1, status: 'Assigned');
        $this->assignment(2, status: 'Accepted');
        $this->assignment(3, status: 'Completed', review: 'Approved');
        $this->assignment(4, status: 'Completed', review: 'Pending Review');
        $this->assignment(5, status: 'Rejected');
        $this->assignment(6, status: 'Assigned', review: 'Not Worked');
        $this->assignment(7, status: 'Completed', review: 'Needs Revision');
        DB::table('assignment_employees')->where('assignment_id', 7)->update([
            'is_late_revision' => true,
        ]);

        $summary = (new EmployeeAssignmentStatistics)->summarize(7);

        $this->assertSame([
            'total' => 7,
            'assigned' => 1,
            'progress' => 1,
            'completed' => 1,
            'cancelled' => 1,
            'pending_review' => 1,
            'needs_revision' => 1,
            'approved' => 1,
            'expired' => 1,
            'not_worked' => 1,
            'late_revision_count' => 1,
        ], $summary);
    }
}
