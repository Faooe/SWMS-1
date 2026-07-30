<?php

namespace App\Http\Requests\Platform;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Sama persis dengan validasi di Platform\PremiumController@update (web),
 * dipakai Api/V1/Platform/PremiumController@update.
 */
class UpdateSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'plan' => [
                'required',
                'in:Premium Go,Premium Plus,Premium Max',
            ],

            'duration' => [
                'required',
                'in:1_month,3_months,12_months',
            ],

        ];
    }

    public function messages(): array
    {
        return [

            'plan.required' => 'Plan wajib dipilih.',
            'plan.in' => 'Plan tidak dikenali.',
            'duration.required' => 'Durasi wajib dipilih.',
            'duration.in' => 'Durasi tidak dikenali.',

        ];
    }
}
