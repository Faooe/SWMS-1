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

        ];
    }
}
