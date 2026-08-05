<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Versi API (dipakai App\Http\Controllers\Api\V1\Employee\EmployeeController
 * & Flutter mobile) dari App\Http\Requests\StoreEmployeeRequest (web).
 * Rules-nya disamakan persis dengan versi web supaya perilaku create
 * employee identik dari kedua sisi -- lihat catatan riwayat perbaikan
 * di versi web soal shift_id/role_id yang dulu wajib diisi padahal
 * tidak pernah dikirim form & tidak dipakai EmployeeService::create().
 */
class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = Auth::user()?->company_id;

        return [

            /*
            |--------------------------------------------------------------------------
            | Employee
            |--------------------------------------------------------------------------
            */

            'employee_number' => [
                'required',
                'max:50',
                Rule::unique('employees', 'employee_number')
                    ->where(fn ($query) => $query->where('company_id', $companyId)),
            ],

            'full_name' => [
                'required',
                'max:150',
            ],

            'email' => [
                'required',
                'email',
                Rule::unique('employees', 'email')
                    ->where(fn ($query) => $query->where('company_id', $companyId)),
            ],

            'phone' => [
                'nullable',
                'max:30',
            ],

            'gender' => [
                'required',
            ],

            'birth_place' => [
                'nullable',
                'max:100',
            ],

            'birth_date' => [
                'nullable',
                'date',
            ],

            'address' => [
                'nullable',
            ],

            'marital_status' => [
                'nullable',
            ],

            'is_active' => [
                'nullable',
            ],

            'photo' => [
                'nullable',
                'image',
                // mimes+max disamakan dengan 'logo' di CompanyRequest/
                // StoreCompanyRequest/UpdateCompanyRequest -- SATU aturan
                // konsisten untuk semua file yang lewat SecureFileService.
                // max:1024 (1MB biner) SENGAJA lebih kecil dari batas lama
                // (2MB) karena sekarang disimpan base64 di kolom 'content'
                // (text) tabel 'files' di Neon Postgres, BUKAN filesystem --
                // base64 menambah ~33% ukuran (1MB biner jadi ~1.4MB
                // tersimpan), dan Neon free tier storage-nya terbatas.
                // 1MB masih longgar untuk foto profil terkompresi (Flutter
                // image_picker sudah imageQuality: 80, lihat
                // employee_form_screen.dart).
                'mimes:jpg,jpeg,png,webp',
                'max:1024',
            ],

            /*
            |--------------------------------------------------------------------------
            | Employment
            |--------------------------------------------------------------------------
            */

            'office_id' => 'nullable|exists:offices,id',

            'department_id' => 'required|exists:departments,id',

            'position_id' => 'required|exists:positions,id',

            'team_id' => 'nullable|exists:teams,id',

            'supervisor_id' => 'nullable|exists:employees,id',

            'employment_type' => 'required|in:Permanent,Contract,Internship',

            'employment_status' => 'required|in:Active,Resigned,Retired,Suspended',

            'start_date' => 'required|date',

            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            */

            'username' => [
                'required',
                'string',
                'min:4',
                'max:50',
            ],

            'user_email' => [
                'required',
                'email',
                'max:150',
                // Global, BUKAN di-scope per company: ini yang jadi
                // users.email, satu-satunya identifier login sekarang.
                Rule::unique('users', 'email'),
            ],

            'password' => [
                'required',
                'min:8',
            ],

        ];
    }

    public function messages(): array
    {
        return [

            'employee_number.unique'
                => 'Employee Number sudah digunakan.',

            'email.unique'
                => 'Email sudah digunakan.',

            'user_email.unique'
                => 'Login Email sudah digunakan.',

            'department_id.required'
                => 'Department wajib dipilih.',

            'position_id.required'
                => 'Position wajib dipilih.',

            'start_date.required'
                => 'Start Date wajib diisi.',

            'photo.image'
                => 'Foto harus berupa gambar.',

            'photo.mimes'
                => 'Foto harus berformat JPG, JPEG, PNG, atau WEBP.',

            'photo.max'
                => 'Ukuran foto maksimal 1MB.',

        ];
    }
}
