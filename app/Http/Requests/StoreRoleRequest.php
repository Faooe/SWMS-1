<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            // Personal
            'employee_number' => 'required|string|max:30|unique:employees',
            'full_name'       => 'required|string|max:150',
            'email'           => 'nullable|email|unique:employees',
            'phone'           => 'nullable|string|max:30',
            'birth_place'     => 'nullable|string|max:100',
            'birth_date'      => 'nullable|date',
            'address'         => 'nullable|string',

            // Employment
            'office_id'          => 'required|exists:offices,id',
            'department_id'      => 'required|exists:departments,id',
            'position_id'        => 'required|exists:positions,id',
            'team_id'            => 'nullable|exists:teams,id',
            'shift_id'           => 'required|exists:shifts,id',
            'supervisor_id'      => 'nullable|exists:employees,id',
            'employment_type'    => 'required',
            'employment_status'  => 'required',
            'start_date'         => 'required|date',

            // Account
            'username' => 'required|string|max:50|unique:users',
            'password' => 'required|min:8',
            'role_id'  => 'required|exists:roles,id',

        ];
    }
}