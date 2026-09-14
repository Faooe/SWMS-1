<?php

namespace Tests\Unit;

use App\Services\Attendance\AttendanceTimeCalculator;
use App\Services\Attendance\HaversineService;
use App\Services\AttendanceService;
use Tests\TestCase;

class AttendanceDistanceTest extends TestCase
{
    public function test_legacy_attendance_service_delegates_distance_calculation(): void
    {
        $service = new AttendanceService(
            new AttendanceTimeCalculator,
            new HaversineService,
        );

        $distance = $service->calculateDistance(0.0, 0.0, 1.0, 0.0);

        $this->assertEqualsWithDelta(111194.93, $distance, 0.1);
        $this->assertTrue($service->isInsideRadius(0.0, 0.0, 0.0, 0.0, 1));
        $this->assertFalse($service->isInsideRadius(0.0, 0.0, 1.0, 0.0, 1000));
    }
}
