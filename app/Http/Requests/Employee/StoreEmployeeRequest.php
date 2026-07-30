<?php

namespace App\Http\Requests\Employee;

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

            /*
            |--------------------------------------------------------------------------
            | Employment
            |--------------------------------------------------------------------------
            */

            'office_id' => 'nullable|exists:offices,id',

            'department_id' => 'required|exists:departments,id',

            'position_id' => 'required|exists:positions,id',

            'team_id' => 'nullable|exists:teams,id',

            'shift_id' => 'required|exists:shifts,id',

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

            'role_id' => [
                'required',
                'exists:roles,id',
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

            'shift_id.required'
                => 'Shift wajib dipilih.',

        ];
    }
}