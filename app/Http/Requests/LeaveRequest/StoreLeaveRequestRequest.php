<?php

namespace App\Http\Requests\LeaveRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'type' => [
                'required',
                'in:Sakit,Acara',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],

            'reason' => [
                'required',
                'string',
                'max:1000',
            ],

        ];
    }

    public function messages(): array
    {
        return [

            'type.required' => 'Jenis izin wajib dipilih.',

            'type.in' => 'Jenis izin tidak valid.',

            'start_date.required' => 'Tanggal mulai wajib diisi.',

            'end_date.required' => 'Tanggal selesai wajib diisi.',

            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',

            'reason.required' => 'Alasan izin wajib diisi.',

        ];
    }
}
