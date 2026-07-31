<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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

            'identity_number' => [
                'nullable',
                'max:50',
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
                Rule::unique('users', 'username')
                    ->where(fn ($query) => $query->where('company_id', $companyId)),
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

            'username.unique'
                => 'Username sudah digunakan.',

            'department_id.required'
                => 'Department wajib dipilih.',

            'position_id.required'
                => 'Position wajib dipilih.',

            'start_date.required'
                => 'Start Date wajib diisi.',

        ];
    }
}