<?php

namespace App\Http\Requests\Assignment;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Company menolak (reject) hasil kerja yang di-submit employee --
 * BEDA dari EmployeeRejectAssignmentRequest (itu employee menolak
 * TAWARAN assignment-nya sendiri sebelum dikerjakan).
 */
class RejectCompletionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'review_notes' => [
                'required',
                'string',
                'min:5',
                'max:2000',
            ],

            // Opsional -- override durasi revisi (menit) khusus untuk
            // reject kali ini, di luar default company
            // (companies.assignment_revision_minutes). Kosongkan untuk
            // pakai default.
            'revision_minutes' => [
                'nullable',
                'integer',
                'min:5',
                'max:43200', // maks 30 hari
            ],

        ];
    }

    public function messages(): array
    {
        return [

            'review_notes.required' => 'Catatan revisi wajib diisi -- jelaskan apa yang perlu diperbaiki employee.',

            'review_notes.min' => 'Catatan revisi minimal 5 karakter.',

            'revision_minutes.integer' => 'Durasi revisi harus berupa angka (menit).',

            'revision_minutes.min' => 'Durasi revisi minimal 5 menit.',

            'revision_minutes.max' => 'Durasi revisi maksimal 30 hari (43200 menit).',

        ];
    }
}
