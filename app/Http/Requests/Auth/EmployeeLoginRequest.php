<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeLoginRequest extends FormRequest
{
    /**
     * Semua orang boleh mengakses endpoint login ini (belum authenticated).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Login khusus Employee: Kode Company + NIP (employee_number) + Password.
     *
     * Kenapa perlu company_code: employee_number sekarang cuma unik PER
     * COMPANY (lihat migration scope_employee_number_email_unique_per_company),
     * jadi NIP "001" bisa dipakai di banyak company sekaligus. Tanpa kode
     * company, sistem tidak tahu company mana yang dimaksud.
     *
     * Ini TIDAK menggantikan login lewat Email (AuthController::login) --
     * dua-duanya tetap aktif berdampingan. Employee yang punya email asli
     * (Gmail dsb.) boleh tetap pakai endpoint /login yang lama seperti
     * Company Admin/Platform Admin. Endpoint ini cuma opsi tambahan untuk
     * employee yang tidak/belum punya email, supaya tidak wajib dibikinkan
     * Gmail cuma demi bisa login.
     */
    public function rules(): array
    {
        return [
            'company_code' => ['required', 'string', 'max:30'],
            'employee_number' => ['required', 'string', 'max:30'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_code.required' => 'Kode Company wajib diisi.',
            'employee_number.required' => 'NIP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ];
    }
}
