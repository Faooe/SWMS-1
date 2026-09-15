<?php

namespace Tests\Unit;

use App\Models\Attendance;
use App\Services\Attendance\AttendanceStatusSummary;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AttendanceStatusSummaryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['database.default' => 'attendance_summary_test']);
        config(['database.connections.attendance_summary_test' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]]);

        Schema::create('attendances', function (Blueprint $table): void {
            $table->id();
            $table->string('attendance_status');
            $table->integer('work_minutes')->nullable();
            $table->softDeletes();
        });
    }

    protected function tearDown(): void
    {
        DB::purge('attendance_summary_test');
        parent::tearDown();
    }

    public function test_it_builds_status_counts_and_optional_work_minutes(): void
    {
        DB::table('attendances')->insert([
            ['attendance_status' => Attendance::STATUS_PRESENT, 'work_minutes' => 480],
            ['attendance_status' => Attendance::STATUS_PRESENT, 'work_minutes' => 420],
            ['attendance_status' => Attendance::STATUS_LATE, 'work_minutes' => 390],
            ['attendance_status' => Attendance::STATUS_LEAVE, 'work_minutes' => 0],
            ['attendance_status' => Attendance::STATUS_PERMISSION, 'work_minutes' => 0],
            ['attendance_status' => Attendance::STATUS_ABSENT, 'work_minutes' => null],
        ]);

        $summary = app(AttendanceStatusSummary::class)
            ->build(Attendance::query(), includeWorkMinutes: true);

        $this->assertSame([
            'present' => 2,
            'late' => 1,
            'leave' => 1,
            'permission' => 1,
            'absent' => 1,
            'total' => 6,
            'work_minutes' => 1290,
        ], $summary);
    }
}
