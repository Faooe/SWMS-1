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
            'profile_photo_url' => $this->profile_photo
                ? secure_file_url($this->profile_photo)
                : ($this->employee?->photo
                    ? secure_file_url($this->employee->photo)
                    : ($this->company?->logo ? secure_file_url($this->company->logo) : null)),

            'role' => [
                'code' => $this->role?->code,
                'name' => $this->role?->name,
            ],

            'company' => [
                'code' => $this->company?->code,
                'name' => $this->company?->name,
                'logo_url' => $this->company?->logo ? secure_file_url($this->company->logo) : null,
            ],

            'employee' => [
                'employee_number' => $this->employee?->employee_number,
                'full_name' => $this->employee?->full_name,
                'email' => $this->employee?->email,
                'phone' => $this->employee?->phone,
                'photo_url' => $this->employee?->photo ? secure_file_url($this->employee->photo) : null,

                'department' => $this->employee?->currentEmployment?->department?->name,

                'position' => $this->employee?->currentEmployment?->position?->name,

                'team' => $this->employee?->currentEmployment?->team?->name,

                'office' => $this->employee?->currentEmployment?->office?->name,

                'shift' => $this->employee?->currentEmployment?->shift?->name,
            ],
        ];
    }
}