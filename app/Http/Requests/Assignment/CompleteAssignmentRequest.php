<?php

namespace App\Http\Requests\Assignment;

use Illuminate\Foundation\Http\FormRequest;

class CompleteAssignmentRequest extends FormRequest
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

            'completion_photo' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png',
                'max:5120', // 5MB
            ],

        ];
    }

    /**
     * Messages
     */
    public function messages(): array
    {
        return [

            'completion_photo.required' => 'Foto bukti selesai wajib diupload.',

            'completion_photo.image' => 'File harus berupa gambar.',

            'completion_photo.max' => 'Ukuran foto maksimal 5MB.',

        ];
    }
}
