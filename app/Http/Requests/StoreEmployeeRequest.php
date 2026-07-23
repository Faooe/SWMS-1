<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Authorization
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
        $companyId = Auth::user()?->company_id;

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
                    ->where(fn ($query) => $query->where('company_id', $companyId)),
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
                    ->where(fn ($query) => $query->where('company_id', $companyId)),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
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
                'nullable',
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
                'string',
                'max:50',
            ],

            'employment_status' => [
                'required',
                'string',
                'max:50',
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
                'unique:users,username',
            ],

            'password' => [
                'required',
                'min:8',
            ],

            'user_is_active' => [
                'nullable',
                'boolean',
            ],

        ];
    }

    /**
     * Custom Messages
     */
    public function messages(): array
    {
        return [

            'employee_number.unique'
                => 'Employee Number sudah digunakan.',

            'email.unique'
                => 'Email sudah digunakan.',

            'username.unique'
                => 'Username sudah digunakan.',

            'password.min'
                => 'Password minimal 8 karakter.',

        ];
    }
}