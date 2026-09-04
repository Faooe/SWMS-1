<?php

namespace App\Http\Requests\Assignment;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentLocationRequest extends FormRequest
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
     *
     * Dipakai untuk Check In / Check Out assignment oleh employee lewat
     * Flutter (butuh koordinat GPS saat itu).
     */
    public function rules(): array
    {
        return [

            'latitude' => ['required', 'numeric', 'between:-90,90'],

            'longitude' => ['required', 'numeric', 'between:-180,180'],

            'work_description' => ['nullable', 'string', 'max:3000'],

            'work_photos' => ['nullable', 'array', 'max:3'],

            'work_photos.*' => ['file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],

        ];
    }
}
