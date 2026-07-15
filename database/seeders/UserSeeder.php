<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $employee = Employee::where(
            'employee_number',
            'EMP-2026-0001'
        )->first();

        $role = Role::where(
            'code',
            'SUPER_ADMIN'
        )->first();

        if (!$employee || !$role) {
            return;
        }

        User::updateOrCreate(

            [
                'username' => 'admin',
            ],

            [
                'employee_id' => $employee->id,

                'role_id' => $role->id,

                'email' => 'admin@swms.test',

                'password' => Hash::make('password'),

                'is_active' => true,
            ]

        );
    }
}