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

            'identity_number' => [
                'nullable',
                'max:50',
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
                'max:2048',
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
                Rule::unique('users', 'username')
                    ->where(fn ($query) => $query->where('company_id', $companyId))
                    ->ignore($userId),
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

            'username.unique'
                => 'Username sudah digunakan.',

            'department_id.required'
                => 'Department wajib dipilih.',

            'position_id.required'
                => 'Position wajib dipilih.',

            'start_date.required'
                => 'Start Date wajib diisi.',

            'password.min'
                => 'Password minimal 8 karakter.',

        ];
    }
}