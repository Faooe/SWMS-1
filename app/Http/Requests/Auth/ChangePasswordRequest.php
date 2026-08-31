<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'current_password' => [
                'required',
                'string'
            ],

            'new_password' => [
                'required',
                'string',
                Password::min(8)->letters()->mixedCase()->numbers(),
                'confirmed'
            ],
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'current_password.required' => 'Password lama wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password minimal 8 karakter.',
            'new_password.password' => 'Password harus memiliki huruf besar, huruf kecil, dan angka.',
            'new_password.confirmed' => 'Konfirmasi password tidak sama.',
        ];
    }
}