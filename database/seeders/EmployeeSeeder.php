<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::updateOrCreate(
            [
                'employee_number' => 'EMP-2026-0001',
            ],
            [
                'full_name' => 'Super Administrator',
                'email' => 'admin@swms.test',
                'phone' => '081234567890',
                'gender' => 'Male',

                'birth_place' => 'Banjarmasin',
                'birth_date' => '2000-01-01',

                'address' => 'Banjarbaru',

                'identity_number' => '6301000000000001',

                'marital_status' => 'Single',

                'photo' => null,

                'emergency_contact_name' => 'Emergency Contact',

                'emergency_contact_phone' => '081234567891',

                'is_active' => true,
            ]
        );
    }
}