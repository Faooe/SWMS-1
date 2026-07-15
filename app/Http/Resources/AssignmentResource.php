<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'code' => $this->code,

            'title' => $this->title,

            'description' => $this->description,

            'location' => [

                'name' => $this->location_name,

                'address' => $this->address,

                'latitude' => $this->latitude,

                'longitude' => $this->longitude,

                'radius' => $this->radius,

            ],

            'schedule' => [

                'start_date' => optional($this->start_date)
                    ->format('Y-m-d'),

                'end_date' => optional($this->end_date)
                    ->format('Y-m-d'),

            ],

            'status' => $this->status,

            'created_by' => [

                'id' => $this->creator?->id,

                'username' => $this->creator?->username,

            ],

            'employees' => $this->assignmentEmployees->map(function ($item) {

                return [

                    'employee_id' => $item->employee_id,

                    'employee_number' => $item->employee?->employee_number,

                    'full_name' => $item->employee?->full_name,

                    'status' => $item->status,

                ];

            }),

            'created_at' => optional($this->created_at)
                ->format('Y-m-d H:i:s'),

        ];
    }
}