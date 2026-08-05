<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Otorisasi company-level (employee milik company lain) sudah
     * dijaga di EmployeeService::update() lewat authorizeCompany().
     * Middleware route ('auth', 'superadmin') sudah membatasi siapa
     * yang boleh sampai ke sini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $companyId = Auth::user()?->company_id;

        // Implicit route model binding: route('employee') sudah
        // ter-resolve jadi instance App\Models\Employee.
        $employee = $this->route('employee');

        $employeeId = $employee?->id;

        $userId = $employee?->user?->id;

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
                    ->where(fn ($query) => $query->where('company_id', $companyId))
                    ->ignore($employeeId),
            ],

            'full_name' => [
                'required',
                'max:150',
            ],

            'email' => [
                'required',
                'email',
                Rule::unique('employees', 'email')
                    ->where(fn ($query) => $query->where('company_id', $companyId))
                    ->ignore($employeeId),
            ],

            'phone' => [
                'nullable',
                'max:30',
            ],

            'gender' => [
                'required',
            ],

            'marital_status' => [
                'nullable',
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

            'emergency_contact_name' => [
                'nullable',
                'max:150',
            ],

            'emergency_contact_phone' => [
                'nullable',
                'max:30',
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
                'mimes:jpg,jpeg,png,webp',
                'max:1024',
            ],

            /*
            |--------------------------------------------------------------------------
            | Employment
            |--------------------------------------------------------------------------
            */

            'department_id' => [
                'required',
                'exists:departments,id',
            ],

            'position_id' => [
                'required',
                'exists:positions,id',
            ],

            'team_id' => [
                'nullable',
                'exists:teams,id',
            ],

            'supervisor_id' => [
                'nullable',
                'exists:employees,id',
            ],

            'office_id' => [
                'nullable',
                'exists:offices,id',
            ],

            'employment_type' => [
                'required',
                'in:Permanent,Contract,Internship',
            ],

            'employment_status' => [
                'required',
                'in:Active,Resigned,Retired,Suspended',
            ],

            'start_date' => [
                'required',
                'date',
            ],

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
                // Global, BUKAN di-scope per company: users.email
                // adalah satu-satunya identifier login.
                Rule::unique('users', 'email')->ignore($userId),
            ],

            // Password bersifat OPSIONAL saat update — kalau dikosongkan,
            // password lama tetap dipakai (lihat EmployeeService::update()).
            'password' => [
                'nullable',
                'min:8',
            ],

            'user_is_active' => [
                'nullable',
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

            'password.min'
                => 'Password minimal 8 karakter.',

            'photo.image'
                => 'Foto harus berupa gambar.',

            'photo.mimes'
                => 'Foto harus berformat JPG, JPEG, PNG, atau WEBP.',

            'photo.max'
                => 'Ukuran foto maksimal 1MB.',

        ];
    }
}
