<?php

namespace App\Http\Requests\Platform;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Versi API (mobile) dari App\Http\Requests\CompanyRequest, khusus untuk
 * CREATE. Otorisasi sudah ditangani middleware 'platform' di routes/api.php
 * (hanya PLATFORM_ADMIN yang bisa sampai ke sini), jadi authorize() di sini
 * cukup true.
 */
class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
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
                Rule::unique('companies', 'code'),
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
            | Super Admin (wajib diisi saat create -- lihat
            | CompanyService::createSuperAdmin())
            |--------------------------------------------------------------------------
            */

            'admin_name' => [
                'required',
                'string',
                'max:150',
            ],

            'admin_email' => [
                'required',
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
                'required',
                'string',
                'min:4',
                'max:50',
                Rule::unique('users', 'username'),
            ],

        ];
    }

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

    public function messages(): array
    {
        return [

            'code.required' => 'Company Code wajib diisi.',
            'code.unique' => 'Company Code sudah digunakan.',
            'name.required' => 'Company Name wajib diisi.',
            'admin_name.required' => 'Nama Super Administrator wajib diisi.',
            'admin_username.required' => 'Username wajib diisi.',
            'admin_username.unique' => 'Username sudah digunakan.',
            'admin_email.required' => 'Email Super Administrator wajib diisi.',
            'admin_email.unique' => 'Email sudah digunakan.',

        ];
    }
}
