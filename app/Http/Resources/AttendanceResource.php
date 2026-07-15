<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'attendance_date' => $this->attendance_date?->format('Y-m-d'),

            'check_in_time' => $this->check_in_time?->format('H:i:s'),

            'check_out_time' => $this->check_out_time?->format('H:i:s'),

            'status' => $this->attendance_status,

            'late_minutes' => $this->late_minutes,

            'office' => [
                'id' => $this->office?->id,
                'code' => $this->office?->code,
                'name' => $this->office?->name,
            ],

            'shift' => [
                'id' => $this->shift?->id,
                'code' => $this->shift?->code,
                'name' => $this->shift?->name,
            ],

            'employee' => [
                'id' => $this->employee?->id,
                'employee_number' => $this->employee?->employee_number,
                'full_name' => $this->employee?->full_name,
            ],

            'check_in_location' => [
                'latitude' => $this->check_in_latitude,
                'longitude' => $this->check_in_longitude,
            ],

            'check_out_location' => [
                'latitude' => $this->check_out_latitude,
                'longitude' => $this->check_out_longitude,
            ],

            'notes' => $this->notes,

            'created_at' => $this->created_at,

        ];
    }
}