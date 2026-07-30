<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Semua user boleh mengakses endpoint login.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validasi request login.
     *
     * Login sekarang HANYA menggunakan Email (bukan lagi username atau NIP),
     * supaya sederhana dan tidak ambigu di sistem multi-company.
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Pesan error yang lebih ramah.
     */
    public function messages(): array
    {
        return [
            'login.required' => 'Email wajib diisi.',
            'login.email' => 'Masukkan alamat email yang valid.',
            'password.required' => 'Password wajib diisi.',
        ];
    }
}