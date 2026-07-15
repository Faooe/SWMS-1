<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'employee_number' => $this->employee_number,

            'full_name' => $this->full_name,

            'email' => $this->email,

            'phone' => $this->phone,

            'gender' => $this->gender,

            'birth_place' => $this->birth_place,

            'birth_date' => optional($this->birth_date)
                ->format('Y-m-d'),

            'marital_status' => $this->marital_status,

            'is_active' => $this->is_active,

            'department' => $this->currentEmployment?->department?->name,

            'position' => $this->currentEmployment?->position?->name,

            'team' => $this->currentEmployment?->team?->name,

            'office' => $this->currentEmployment?->office?->name,

            'shift' => $this->currentEmployment?->shift?->name,

            'created_at' => optional($this->created_at)
                ->format('Y-m-d H:i:s'),

        ];
    }
}