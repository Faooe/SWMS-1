<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Authorize
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        $employee = $this->route('employee');

        $companyId = Auth::user()?->company_id ?? $employee->company_id;

        return [

            /*
            |--------------------------------------------------------------------------
            | Employee
            |--------------------------------------------------------------------------
            */

            'employee_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('employees', 'employee_number')
                    ->where(fn ($query) => $query->where('company_id', $companyId))
                    ->ignore($employee),
            ],

            'full_name' => [
                'required',
                'string',
                'max:150',
            ],

            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('employees', 'email')
                    ->where(fn ($query) => $query->where('company_id', $companyId))
                    ->ignore($employee),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'gender' => [
                'required',
                Rule::in([
                    'Male',
                    'Female',
                ]),
            ],

            'birth_place' => [
                'nullable',
                'string',
                'max:100',
            ],

            'birth_date' => [
                'nullable',
                'date',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'identity_number' => [
                'nullable',
                'digits_between:1,9',
            ],

            'marital_status' => [
                'nullable',
                Rule::in([
                    'Single',
                    'Married',
                    'Divorced',
                ]),
            ],

            'is_active' => [
                'required',
                'boolean',
            ],

            /*
            |--------------------------------------------------------------------------
            | Employment
            |--------------------------------------------------------------------------
            */

            'office_id' => [
                'required',
                'exists:offices,id',
            ],

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

            'employment_type' => [
                'required',
                Rule::in([
                    'Permanent',
                    'Contract',
                    'Internship',
                ]),
            ],

            'employment_status' => [
                'required',
                Rule::in([
                    'Active',
                    'Probation',
                    'Resigned',
                ]),
            ],

            'start_date' => [
                'required',
                'date',
            ],

            /*
            |--------------------------------------------------------------------------
            | User Account
            |--------------------------------------------------------------------------
            */

            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'username')
                    ->ignore(optional($employee->user)->id),
            ],

            'user_email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')
                    ->ignore(optional($employee->user)->id),
            ],

            // Password baru (opsional)
            'password' => [
                'nullable',
                'string',
                'min:8',
            ],

            'user_is_active' => [
                'required',
                'boolean',
            ],

            /*
            |--------------------------------------------------------------------------
            | Photo
            |--------------------------------------------------------------------------
            */

            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

        ];
    }

    /**
     * Custom Messages
     */
    public function messages(): array
    {
        return [

            'employee_number.unique' =>
                'Employee Number sudah digunakan.',

            'email.unique' =>
                'Email sudah digunakan.',

            'username.unique' =>
                'Username sudah digunakan.',

            'user_email.unique' =>
                'Login Email sudah digunakan.',

            'password.min' =>
                'Password minimal 8 karakter.',

        ];
    }
}