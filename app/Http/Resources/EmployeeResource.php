<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * Dipakai untuk List, Detail, DAN prefill form Edit di Flutter --
     * jadi selain nama department/position/dst (buat ditampilkan),
     * ID mentahnya juga disertakan (buat pre-select dropdown pas edit).
     * Sebelumnya resource ini cuma punya nama, bukan ID, jadi gak
     * cukup buat form edit.
     */
    public function toArray(Request $request): array
    {
        $employment = $this->currentEmployment;

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

            'address' => $this->address,

            'marital_status' => $this->marital_status,

            'is_active' => $this->is_active,

            'photo_url' => $this->photo
                ? secure_file_url($this->photo)
                : null,

            /*
            |--------------------------------------------------------------------------
            | Employment (nama untuk ditampilkan + ID untuk form edit)
            |--------------------------------------------------------------------------
            */

            'department' => $employment?->department?->name,
            'department_id' => $employment?->department_id,

            'position' => $employment?->position?->name,
            'position_id' => $employment?->position_id,

            'team' => $employment?->team?->name,
            'team_id' => $employment?->team_id,

            'office' => $employment?->office?->name,
            'office_id' => $employment?->office_id,

            'shift' => $employment?->shift?->name,
            'shift_id' => $employment?->shift_id,

            'supervisor' => $employment?->supervisor?->full_name,
            'supervisor_id' => $employment?->supervisor_id,

            'employment_type' => $employment?->employment_type,

            'employment_status' => $employment?->employment_status,

            'start_date' => optional($employment?->start_date)
                ->format('Y-m-d'),

            /*
            |--------------------------------------------------------------------------
            | Account / Login (username & email login -- BEDA dari email
            | pribadi employee di atas)
            |--------------------------------------------------------------------------
            */

            'username' => $this->user?->username,

            'user_email' => $this->user?->email,

            'created_at' => optional($this->created_at)
                ->format('Y-m-d H:i:s'),

        ];
    }
}
