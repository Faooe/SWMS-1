<?php

namespace App\Services\Attendance;

use App\Models\Attendance;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class AttendanceTimeCalculator
{
    /** @return array{status: string, late_minutes: int} */
    public function checkInStatus(
        string $startTime,
        int $toleranceMinutes,
        ?CarbonInterface $checkedInAt = null
    ): array {
        $checkIn = $checkedInAt ?? now();
        $start = $checkIn->copy()->startOfDay()->setTimeFromTimeString($startTime);
        $deadline = $start->copy()->addMinutes($toleranceMinutes);

        if ($checkIn->lessThanOrEqualTo($deadline)) {
            return ['status' => 'Present', 'late_minutes' => 0];
        }

        return [
            'status' => 'Late',
            'late_minutes' => (int) round(abs($start->diffInMinutes($checkIn))),
        ];
    }

    /**
     * Calculate the persisted duration values for an attendance checkout.
     *
     * @return array{work_minutes: int, early_leave_minutes: int, overtime_minutes: int}
     */
    public function checkoutMetrics(
        Attendance $attendance,
        string $expectedEndTime,
        ?CarbonInterface $checkedOutAt = null
    ): array {
        $date = $attendance->attendance_date?->toDateString() ?? today()->toDateString();
        $rawCheckIn = $attendance->getRawOriginal('check_in_time')
            ?: $attendance->check_in_time?->format('H:i:s');

        $checkIn = Carbon::parse($date.' '.$rawCheckIn);
        $checkOut = $checkedOutAt ?? now();
        $expectedEnd = Carbon::parse($date.' '.$expectedEndTime);

        return [
            'work_minutes' => max(0, (int) round($checkIn->diffInMinutes($checkOut))),
            'early_leave_minutes' => $checkOut->lt($expectedEnd)
                ? max(0, (int) round($checkOut->diffInMinutes($expectedEnd)))
                : 0,
            'overtime_minutes' => $checkOut->gt($expectedEnd)
                ? max(0, (int) round($expectedEnd->diffInMinutes($checkOut)))
                : 0,
        ];
    }
}
