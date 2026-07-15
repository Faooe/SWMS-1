<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class CheckInRequest extends FormRequest
{
    /**
     * Authorize request.
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

            'latitude' => [
                'required',
                'numeric',
                'between:-90,90'
            ],

            'longitude' => [
                'required',
                'numeric',
                'between:-180,180'
            ],

            'notes' => [
                'nullable',
                'string',
                'max:500'
            ],

        ];
    }

    /**
     * Custom messages.
     */
    public function messages(): array
    {
        return [

            'latitude.required' => 'Latitude wajib diisi.',

            'longitude.required' => 'Longitude wajib diisi.',

            'latitude.numeric' => 'Latitude harus berupa angka.',

            'longitude.numeric' => 'Longitude harus berupa angka.',

        ];
    }
}