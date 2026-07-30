<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,

            'username' => $this->username,
            'email' => $this->email,

            'role' => [
                'code' => $this->role?->code,
                'name' => $this->role?->name,
            ],

            'employee' => [
                'employee_number' => $this->employee?->employee_number,
                'full_name' => $this->employee?->full_name,
                'email' => $this->employee?->email,
                'phone' => $this->employee?->phone,

                'department' => $this->employee?->currentEmployment?->department?->name,

                'position' => $this->employee?->currentEmployment?->position?->name,

                'team' => $this->employee?->currentEmployment?->team?->name,

                'office' => $this->employee?->currentEmployment?->office?->name,

                'shift' => $this->employee?->currentEmployment?->shift?->name,
            ],
        ];
    }
}