<?php

namespace App\Services\Attendance;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Builder;

/** Builds the canonical status totals shared by attendance summaries. */
class AttendanceStatusSummary
{
    public function build(Builder $query, bool $includeWorkMinutes = false): array
    {
        $summary = [
            'present' => (clone $query)->where('attendance_status', Attendance::STATUS_PRESENT)->count(),
            'late' => (clone $query)->where('attendance_status', Attendance::STATUS_LATE)->count(),
            'leave' => (clone $query)->where('attendance_status', Attendance::STATUS_LEAVE)->count(),
            'permission' => (clone $query)->where('attendance_status', Attendance::STATUS_PERMISSION)->count(),
            'absent' => (clone $query)->where('attendance_status', Attendance::STATUS_ABSENT)->count(),
            'total' => (clone $query)->count(),
        ];

        if ($includeWorkMinutes) {
            $summary['work_minutes'] = (int) ((clone $query)->sum('work_minutes') ?? 0);
        }

        return $summary;
    }
}
