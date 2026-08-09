<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MasterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'uuid' => $this->uuid ?? null,

            'code' => $this->code,

            'name' => $this->name,

            'is_active' => $this->is_active ?? true,

            // Cuma terisi untuk Team (Team punya kolom department_id --
            // model master lain seperti Position/Office/Shift tidak
            // punya kolom ini, jadi otomatis null lewat null-safe
            // property access di bawah, aman tanpa perlu resource
            // terpisah per tipe master). Dipakai mobile & web untuk
            // filter cascading "pilih Department dulu baru Team yang
            // sesuai muncul" -- lihat resources/views/components/
            // employee/forms/employment-information.blade.php.
            'department_id' => $this->department_id ?? null,

        ];
    }
}