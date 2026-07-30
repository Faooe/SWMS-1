<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
{
    /**
     * Authorization
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
        $companyId = $this->route('company')?->id;

        return [

            /*
            |--------------------------------------------------------------------------
            | Company
            |--------------------------------------------------------------------------
            */

            'code' => [

                'required',

                'string',

                'max:30',

                Rule::unique('companies', 'code')
                    ->ignore($companyId),

            ],

            'name' => [

                'required',

                'string',

                'max:150',

            ],

            'email' => [

                'nullable',

                'email',

                'max:150',

            ],

            'phone' => [

                'nullable',

                'string',

                'max:30',

            ],

            'website' => [

                'nullable',

                'url',

                'max:255',

            ],

            'address' => [

                'nullable',

                'string',

            ],

            'city' => [

                'nullable',

                'string',

                'max:100',

            ],

            'province' => [

                'nullable',

                'string',

                'max:100',

            ],

            'postal_code' => [

                'nullable',

                'string',

                'max:15',

            ],

            'latitude' => [

                'nullable',

                'numeric',

                'between:-90,90',

            ],

            'longitude' => [

                'nullable',

                'numeric',

                'between:-180,180',

            ],
            'polygon' => [

                'nullable',

                'string',

            ],

            'timezone' => [

                'nullable',

                'string',

                'max:50',

            ],

            'logo' => [

                'nullable',

                'image',

                'mimes:jpg,jpeg,png,webp',

                'max:2048',

            ],

            /*
            |--------------------------------------------------------------------------
            | Super Admin
            |--------------------------------------------------------------------------
            */

            'admin_name' => [

                Rule::requiredIf(!$companyId),

                'string',

                'max:150',

            ],

            'admin_email' => [

                Rule::requiredIf(!$companyId),

                'email',

                'max:150',

                Rule::unique('users', 'email'),

            ],

            'admin_phone' => [

                'nullable',

                'string',

                'max:30',

            ],

            'admin_username' => [

                Rule::requiredIf(!$companyId),

                'string',

                'min:4',

                'max:50',

                Rule::unique('users', 'username'),

            ],

        ];
    }

    /**
     * Attribute Names
     */
    public function attributes(): array
    {
        return [

            'code' => 'Company Code',

            'name' => 'Company Name',

            'email' => 'Company Email',

            'phone' => 'Company Phone',

            'website' => 'Website',

            'address' => 'Address',

            'city' => 'City',

            'province' => 'Province',

            'postal_code' => 'Postal Code',

            'timezone' => 'Timezone',

            'logo' => 'Company Logo',

            'admin_name' => 'Super Admin Name',

            'admin_email' => 'Super Admin Email',

            'admin_phone' => 'Super Admin Phone',

            'admin_username' => 'Super Admin Username',

        ];
    }
}