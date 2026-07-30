<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class CheckOutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
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
        return [

            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:500',
            ],

        ];
    }
}