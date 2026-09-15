<?php

namespace Tests\Unit;

use App\Support\AssignmentFormAttributes;
use PHPUnit\Framework\TestCase;

class AssignmentFormAttributesTest extends TestCase
{
    private function formData(): array
    {
        return [
            'title' => 'Inspeksi kantor',
            'office_id' => 2,
            'location_name' => 'Head Office',
            'latitude' => -3.3,
            'longitude' => 114.6,
            'radius' => 80,
            'priority' => 'High',
            'assignment_type' => 'Inspection',
            'start_datetime' => '2026-09-14 08:00:00',
            'end_datetime' => '2026-09-14 17:00:00',
        ];
    }

    public function test_defaults_and_radius_are_preserved_without_polygon(): void
    {
        $attributes = AssignmentFormAttributes::build($this->formData(), 'Draft', null);

        $this->assertSame(80, $attributes['radius']);
        $this->assertNull($attributes['polygon']);
        $this->assertSame('Draft', $attributes['status']);
        $this->assertNull($attributes['description']);
        $this->assertNull($attributes['address']);
        $this->assertFalse($attributes['daily_attendance_enabled']);
        $this->assertSame('WORK_CALENDAR', $attributes['attendance_day_rule']);
    }

    public function test_polygon_replaces_radius_and_status_can_be_overridden(): void
    {
        $polygon = [[-3.3, 114.6], [-3.4, 114.7], [-3.5, 114.8]];
        $attributes = AssignmentFormAttributes::build([
            ...$this->formData(),
            'description' => 'Periksa peralatan',
            'daily_attendance_enabled' => 1,
            'attendance_day_rule' => 'EVERY_DAY',
        ], 'Assigned', $polygon);

        $this->assertNull($attributes['radius']);
        $this->assertSame($polygon, $attributes['polygon']);
        $this->assertSame('Assigned', $attributes['status']);
        $this->assertSame('Periksa peralatan', $attributes['description']);
        $this->assertTrue($attributes['daily_attendance_enabled']);
        $this->assertSame('EVERY_DAY', $attributes['attendance_day_rule']);
    }
}
