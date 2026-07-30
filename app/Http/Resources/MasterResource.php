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

        ];
    }
}