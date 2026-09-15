<?php

namespace App\Support;

class AssignmentFormAttributes
{
    /** Build the fields shared by assignment create and update forms. */
    public static function build(array $data, string $status, ?array $polygon): array
    {
        return [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'office_id' => $data['office_id'],
            'location_name' => $data['location_name'],
            'address' => $data['address'] ?? null,
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'radius' => $polygon ? null : ($data['radius'] ?? null),
            'polygon' => $polygon,
            'priority' => $data['priority'],
            'assignment_type' => $data['assignment_type'],
            'status' => $status,
            'start_datetime' => $data['start_datetime'],
            'end_datetime' => $data['end_datetime'],
            'daily_attendance_enabled' => (bool) ($data['daily_attendance_enabled'] ?? false),
            'attendance_day_rule' => $data['attendance_day_rule'] ?? 'WORK_CALENDAR',
        ];
    }
}
