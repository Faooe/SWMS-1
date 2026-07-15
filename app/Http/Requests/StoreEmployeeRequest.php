<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
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
                'unique:employees,employee_number',
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
                'unique:employees,email',
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
                'string',
                'max:50',
            ],

            'marital_status' => [
                'nullable',
                Rule::in([
                    'Single',
                    'Married',
                    'Divorced',
                ]),
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