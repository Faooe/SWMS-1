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
        $company = $this->route('company');

        $companyId = $company?->id;

        // Cari user Super Admin milik company ini (kalau sedang edit), supaya
        // validasi unique di bawah tidak salah menganggap admin_email/
        // admin_username miliknya sendiri sebagai "sudah dipakai".
        $adminUserId = $companyId
            ? $company->users()
                ->whereHas('role', fn ($q) => $q->where('code', 'SUPER_ADMIN'))
                ->value('id')
            : null;

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

                // Global, BUKAN di-scope per company: users.email adalah
                // satu-satunya identifier login, jadi harus unik lintas
                // company. Saat edit, abaikan email milik admin ini sendiri.
                Rule::unique('users', 'email')
                    ->ignore($adminUserId),

            ],

            'admin_phone' => [

                'nullable',

                'string',

                'max:30',

            ],

            'admin_username' => array_filter([

                Rule::requiredIf(!$companyId),

                'string',

                'min:4',

                'max:50',

                $companyId
                    ? Rule::unique('users', 'username')
                        ->where(fn ($query) => $query->where('company_id', $companyId))
                        ->ignore($adminUserId)
                    : null,

            ]),

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