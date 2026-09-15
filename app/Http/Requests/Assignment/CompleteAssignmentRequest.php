<?php

namespace App\Http\Requests\Assignment;

use App\Rules\MediaUploadSize;
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
     *
     * Sebelumnya cuma 1 foto wajib, tanpa catatan pekerjaan sama sekali.
     * Sekarang:
     * - completion_photo: WAJIB (foto/video pertama)
     * - completion_photo_2: OPSIONAL (foto/video kedua, boleh dikosongkan)
     * - completion_notes: WAJIB, detail apa saja yang dikerjakan/
     *   diperbaiki employee.
     *
     * Batas ukuran ditegakkan oleh MediaUploadSize: foto 200KB, video 5MB.
     */
    public function rules(): array
    {
        return [

            'completion_photo' => [
                'required',
                'file',
                'mimes:jpeg,jpg,png,webp,mp4,mov,webm,pdf',
                new MediaUploadSize,
            ],

            'completion_photo_2' => [
                'nullable',
                'file',
                'mimes:jpeg,jpg,png,webp,mp4,mov,webm,pdf',
                new MediaUploadSize,
            ],

            'completion_notes' => [
                'required',
                'string',
                'min:10',
                'max:2000',
            ],

        ];
    }

    /**
     * Messages
     */
    public function messages(): array
    {
        return [

            'completion_photo.required' => 'Bukti selesai (foto atau video pertama) wajib diupload.',

            'completion_photo.mimes' => 'Bukti pertama harus berupa foto (JPG/PNG/WEBP), PDF, atau video (MP4/MOV/WEBM).',

            'completion_photo_2.mimes' => 'Bukti kedua harus berupa foto (JPG/PNG/WEBP), PDF, atau video (MP4/MOV/WEBM).',

            'completion_notes.required' => 'Catatan detail pekerjaan wajib diisi.',

            'completion_notes.min' => 'Catatan detail pekerjaan minimal 10 karakter.',

            'completion_notes.max' => 'Catatan detail pekerjaan maksimal 2000 karakter.',

        ];
    }
}
