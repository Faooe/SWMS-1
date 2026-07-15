<?php

namespace App\Http\Requests\Platform;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends FormRequest
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
        return [

            'current_password' => [
                'required',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],

        ];
    }

    /**
     * Messages
     */
    public function messages(): array
    {
        return [

            'current_password.required' =>
                'Password lama wajib diisi.',

            'password.required' =>
                'Password baru wajib diisi.',

            'password.confirmed' =>
                'Konfirmasi password tidak sama.',

        ];
    }
}