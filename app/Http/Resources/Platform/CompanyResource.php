<?php

namespace App\Http\Resources\Platform;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * Dipakai oleh Api/V1/Platform/DashboardController & CompanyController.
 *
 * Struktur ini sengaja dibuat mudah dipetakan ke CompanySummaryItem /
 * PlatformDashboardSummary di sisi Flutter (lib/features/platform/domain/
 * company_summary_model.dart) -- lihat catatan di masing-masing field.
 */
class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'uuid' => $this->uuid,

            // -> CompanySummaryItem.code
            'code' => $this->code,

            // -> CompanySummaryItem.name
            'name' => $this->name,

            'email' => $this->email,

            'phone' => $this->phone,

            'website' => $this->website,

            'logo_url' => $this->logo
                ? Storage::disk('public')->url($this->logo)
                : null,

            'address' => $this->address,

            'city' => $this->city,

            'province' => $this->province,

            'postal_code' => $this->postal_code,

            'timezone' => $this->timezone,

            /*
            |--------------------------------------------------------------------------
            | Lokasi (dari Head Office)
            |--------------------------------------------------------------------------
            |
            | Company tidak punya kolom latitude/longitude/polygon sendiri
            | -- datanya ada di record Office (is_head_office = true)
            | lewat relasi headOffice(), lihat Company::headOffice(). Pakai
            | whenLoaded supaya endpoint index/list (yang tidak nge-load
            | relasi ini demi performa, lihat CompanyService::getAll())
            | tidak memicu query tambahan per baris -- field ini otomatis
            | jadi null di situ, dan baru terisi di endpoint detail
            | (CompanyService::find(), yang eager-load 'headOffice').
            |
            */

            'latitude' => $this->whenLoaded(
                'headOffice',
                fn () => $this->headOffice?->latitude
            ),

            'longitude' => $this->whenLoaded(
                'headOffice',
                fn () => $this->headOffice?->longitude
            ),

            'polygon' => $this->whenLoaded(
                'headOffice',
                fn () => $this->headOffice?->polygon
            ),

            // -> CompanySummaryItem.isActive
            'is_active' => (bool) $this->is_active,

            'subscription' => [

                // -> CompanySummaryItem.planLabel (nilainya sudah berupa
                // label yang enak ditampilkan, mis. "Free" / "Premium Go")
                'plan' => $this->subscription_plan,

                // -> CompanySummaryItem.isPremiumPlan
                'is_premium' => $this->isPremium(),

                'start' => optional($this->subscription_start)
                    ->format('Y-m-d'),

                'end' => optional($this->subscription_end)
                    ->format('Y-m-d'),

                // -> CompanySummaryItem.employeeLimit
                'max_employee' => $this->max_employee,

            ],

            /*
            |--------------------------------------------------------------------------
            | Counts (hanya terisi kalau query-nya pakai withCount, lihat
            | CompanyService::getAll() / find())
            |--------------------------------------------------------------------------
            */

            'counts' => [

                'users' => $this->whenCounted('users'),

                'employees' => $this->whenCounted('employees'),

                'offices' => $this->whenCounted('offices'),

                'assignments' => $this->whenCounted('assignments'),

                'attendances' => $this->whenCounted('attendances'),

            ],

            'created_at' => optional($this->created_at)
                ->format('Y-m-d H:i:s'),

        ];
    }
}
