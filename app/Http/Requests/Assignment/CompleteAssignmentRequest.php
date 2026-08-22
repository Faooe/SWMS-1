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
     *
     * Sebelumnya cuma 1 foto wajib, tanpa catatan pekerjaan sama sekali.
     * Sekarang:
     * - completion_photo: WAJIB (foto pertama)
     * - completion_photo_2: OPSIONAL (foto kedua, boleh dikosongkan)
     * - completion_notes: WAJIB, detail apa saja yang dikerjakan/
     *   diperbaiki employee.
     *
     * max:300 (KB) -- mobile SUDAH mengkompres foto ke bawah 300KB
     * sebelum upload (lihat image_compress_helper.dart), ini cuma
     * safety-net kalau ada client lain (web/API pihak ketiga) yang
     * kirim tanpa kompresi.
     */
    public function rules(): array
    {
        return [

            'completion_photo' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png',
                'max:300',
            ],

            'completion_photo_2' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png',
                'max:300',
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

            'completion_photo.required' => 'Foto bukti selesai (foto pertama) wajib diupload.',

            'completion_photo.image' => 'File harus berupa gambar.',

            'completion_photo.max' => 'Ukuran foto pertama maksimal 300KB.',

            'completion_photo_2.image' => 'File harus berupa gambar.',

            'completion_photo_2.max' => 'Ukuran foto kedua maksimal 300KB.',

            'completion_notes.required' => 'Catatan detail pekerjaan wajib diisi.',

            'completion_notes.min' => 'Catatan detail pekerjaan minimal 10 karakter.',

            'completion_notes.max' => 'Catatan detail pekerjaan maksimal 2000 karakter.',

        ];
    }
}
