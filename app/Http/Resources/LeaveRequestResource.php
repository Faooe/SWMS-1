<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            'type' => $this->type,

            'start_date' => optional($this->start_date)->format('Y-m-d'),

            'end_date' => optional($this->end_date)->format('Y-m-d'),

            'duration' => $this->duration,

            'reason' => $this->reason,

            'status' => $this->status,

            'rejection_reason' => $this->rejection_reason,

            'is_auto_rejected' => $this->status === 'Rejected'
                && $this->approved_by === null
                && $this->rejection_reason === \App\Services\LeaveRequestService::AUTO_REJECT_REASON,

            'employee' => [

                'id' => $this->employee?->id,

                'employee_number' => $this->employee?->employee_number,

                'full_name' => $this->employee?->full_name,

            ],

            'approver' => $this->approver ? [

                'id' => $this->approver->id,

                'username' => $this->approver->username,

            ] : null,

            'approved_at' => optional($this->approved_at)->format('Y-m-d H:i:s'),

            'created_at' => optional($this->created_at)->format('Y-m-d H:i:s'),

        ];
    }
}
