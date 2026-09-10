<?php

namespace Tests\Unit;

use App\Models\Assignment;
use App\Services\EmployeeAssignmentOrdering;
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
            $table->string('status');
            $table->string('priority');
            $table->dateTime('start_datetime')->nullable();
            $table->dateTime('end_datetime')->nullable();
            $table->dateTime('created_at');
            $table->softDeletes();
        });
        Schema::create('assignment_employees', function (Blueprint $table) {
            $table->integer('assignment_id');
            $table->integer('employee_id');
            $table->string('status');
            $table->string('review_status')->nullable();
        });
    }

    protected function tearDown(): void
    {
        DB::purge('ordering_test');
        parent::tearDown();
    }

    private function assignment(int $id, string $priority = 'High', string $status = 'Assigned', ?string $review = null, ?string $deadline = '2026-09-20 17:00:00', string $created = '2026-09-10 08:00:00', string $global = 'Assigned'): void
    {
        DB::table('assignments')->insert([
            'id' => $id, 'priority' => $priority, 'status' => $global,
            'start_datetime' => '2026-09-10 08:00:00',
            'end_datetime' => $deadline, 'created_at' => $created,
        ]);
        DB::table('assignment_employees')->insert([
            'assignment_id' => $id, 'employee_id' => 7,
            'status' => $status, 'review_status' => $review,
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
}
