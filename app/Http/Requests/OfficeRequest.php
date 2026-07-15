<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OfficeRequest extends FormRequest
{
    /**
     * Authorize
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        $officeId = $this->route('office')?->id;

        return [

            'code' => [

                'required',

                'string',

                'max:20',

                Rule::unique('offices', 'code')

                    ->ignore($officeId),

            ],

            'name' => [

                'required',

                'string',

                'max:150',

            ],

            'address' => [

                'required',

                'string',

            ],

            'province' => [

                'nullable',

                'string',

                'max:100',

            ],

            'city' => [

                'nullable',

                'string',

                'max:100',

            ],

            'postal_code' => [

                'nullable',

                'string',

                'max:10',

            ],

            'timezone' => [

                'required',

                'string',

                'max:50',

            ],

            'latitude' => [

                'required',

                'numeric',

            ],

            'longitude' => [

                'required',

                'numeric',

            ],

            'radius' => [

                'required',

                'integer',

                'min:50',

                'max:5000',

            ],

            'polygon' => [

                'nullable',

                'string',

            ],

            'is_active' => [

                'boolean',

            ],

            'is_head_office' => [

                'boolean',

            ],

        ];
    }

    /**
     * Validation Messages
     */
    public function messages(): array
    {
        return [

            'code.required' => 'Office code is required.',

            'code.unique' => 'Office code already exists.',

            'name.required' => 'Office name is required.',

            'address.required' => 'Office address is required.',

            'latitude.required' => 'Latitude is required.',

            'longitude.required' => 'Longitude is required.',

            'radius.required' => 'Attendance radius is required.',

            'radius.min' => 'Minimum attendance radius is 50 meters.',

        ];
    }
}