<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
{
    /**
     * Authorize
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Rules
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

            'logo' => [

                'nullable',

                'image',

                'mimes:jpg,jpeg,png,svg',

                'max:2048',

            ],

            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */

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

            'timezone' => [

                'nullable',
                'string',
                'max:50',

            ],

            /*
            |--------------------------------------------------------------------------
            | Super Admin
            |--------------------------------------------------------------------------
            */

            'admin_name' => [

                Rule::requiredIf(
                    $this->isMethod('post')
                ),

                'string',

                'max:150',

            ],

            'admin_username' => [

                Rule::requiredIf(
                    $this->isMethod('post')
                ),

                'string',

                'min:4',

                'max:50',

                Rule::unique('users', 'username'),

            ],

            'admin_email' => [

                Rule::requiredIf(
                    $this->isMethod('post')
                ),

                'email',

                Rule::unique('users', 'email'),

            ],

            'admin_phone' => [

                'nullable',

                'string',

                'max:30',

            ],

            'admin_gender' => [

                'nullable',

                Rule::in([
                    'Male',
                    'Female'
                ]),

            ],

        ];
    }

    /**
     * Attributes
     */
    public function attributes(): array
    {
        return [

            'code' => 'Company Code',

            'name' => 'Company Name',

            'email' => 'Company Email',

            'phone' => 'Company Phone',

            'website' => 'Website',

            'logo' => 'Company Logo',

            'address' => 'Address',

            'city' => 'City',

            'province' => 'Province',

            'postal_code' => 'Postal Code',

            'admin_name' => 'Administrator Name',

            'admin_username' => 'Administrator Username',

            'admin_email' => 'Administrator Email',

            'admin_phone' => 'Administrator Phone',

            'admin_gender' => 'Administrator Gender',

        ];
    }

    /**
     * Messages
     */
    public function messages(): array
    {
        return [

            'code.required' => 'Company Code wajib diisi.',

            'code.unique' => 'Company Code sudah digunakan.',

            'name.required' => 'Company Name wajib diisi.',

            'admin_name.required' => 'Nama Super Administrator wajib diisi.',

            'admin_username.required' => 'Username wajib diisi.',

            'admin_username.unique' => 'Username sudah digunakan.',

            'admin_email.required' => 'Email wajib diisi.',

            'admin_email.unique' => 'Email sudah digunakan.',

            'logo.image' => 'Logo harus berupa gambar.',

            'logo.max' => 'Ukuran logo maksimal 2 MB.',

            'website.url' => 'Website harus berupa URL yang valid.',

        ];
    }
}