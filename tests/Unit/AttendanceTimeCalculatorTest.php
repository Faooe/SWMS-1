<?php

namespace Tests\Unit;

use App\Models\Attendance;
use App\Services\Attendance\AttendanceTimeCalculator;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AttendanceTimeCalculatorTest extends TestCase
{
    #[DataProvider('checkInCases')]
    public function test_it_applies_the_same_lateness_rule_to_both_attendance_flows(
        string $checkedInAt,
        array $expected
    ): void {
        $this->assertSame(
            $expected,
            (new AttendanceTimeCalculator)->checkInStatus(
                '08:00:00',
                15,
                Carbon::parse($checkedInAt)
            )
        );
    }

    public static function checkInCases(): array
    {
        return [
            'before start' => [
                '2026-09-11 07:55:00',
                ['status' => 'Present', 'late_minutes' => 0],
            ],
            'at tolerance boundary' => [
                '2026-09-11 08:15:00',
                ['status' => 'Present', 'late_minutes' => 0],
            ],
            'after tolerance boundary' => [
                '2026-09-11 08:16:00',
                ['status' => 'Late', 'late_minutes' => 16],
            ],
        ];
    }

    #[DataProvider('checkoutCases')]
    public function test_it_calculates_checkout_durations(
        string $checkedOutAt,
        array $expected
    ): void {
        $attendance = new Attendance;
        $attendance->forceFill([
            'attendance_date' => '2026-09-11',
            'check_in_time' => '08:00:00',
        ]);

        $metrics = (new AttendanceTimeCalculator)->checkoutMetrics(
            $attendance,
            '17:00:00',
            Carbon::parse($checkedOutAt)
        );

        $this->assertSame($expected, $metrics);
    }

    public static function checkoutCases(): array
    {
        return [
            'early checkout' => [
                '2026-09-11 16:30:00',
                [
                    'work_minutes' => 510,
                    'early_leave_minutes' => 30,
                    'overtime_minutes' => 0,
                ],
            ],
            'on time checkout' => [
                '2026-09-11 17:00:00',
                [
                    'work_minutes' => 540,
                    'early_leave_minutes' => 0,
                    'overtime_minutes' => 0,
                ],
            ],
            'overtime checkout' => [
                '2026-09-11 18:15:00',
                [
                    'work_minutes' => 615,
                    'early_leave_minutes' => 0,
                    'overtime_minutes' => 75,
                ],
            ],
        ];
    }
}
