<?php

namespace App\Http\Requests\Platform;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Versi API (mobile) dari App\Http\Requests\CompanyRequest, khusus untuk
 * UPDATE. Sengaja TIDAK memuat field admin_* -- CompanyService::update()
 * hanya mengubah data company, bukan kredensial Super Admin-nya (sama
 * seperti perilaku web).
 */
class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = $this->route('company')?->id;

        return [

            'code' => [
                'required',
                'string',
                'max:30',
                Rule::unique('companies', 'code')->ignore($companyId),
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

        ];
    }

    public function messages(): array
    {
        return [

            'code.required' => 'Company Code wajib diisi.',
            'code.unique' => 'Company Code sudah digunakan.',
            'name.required' => 'Company Name wajib diisi.',

        ];
    }
}
