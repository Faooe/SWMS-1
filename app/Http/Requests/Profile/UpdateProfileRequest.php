<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
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
        $userId = Auth::id();

        return [

            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'username')->ignore($userId),
            ],

            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],

            'current_password' => [
                'required_with:password',
                'string',
            ],

            'password' => [
                'nullable',
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

            'username.unique' => 'Username sudah digunakan.',

            'email.unique' => 'Email sudah digunakan.',

            'current_password.required_with' =>
                'Password lama wajib diisi untuk mengubah password.',

            'password.confirmed' => 'Konfirmasi password tidak sama.',

        ];
    }
}
