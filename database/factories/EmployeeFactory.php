<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        static $employeeNumber = 1;

        return [

            'uuid' => (string) Str::uuid(),

            'employee_number' =>
                'EMP-' .
                now()->format('Y') .
                '-' .
                str_pad($employeeNumber++, 4, '0', STR_PAD_LEFT),

            'full_name' => fake()->name(),

            'email' => fake()->unique()->safeEmail(),

            'phone' => fake()->phoneNumber(),

            'gender' => fake()->randomElement([
                'Male',
                'Female',
            ]),

            'birth_place' => fake()->city(),

            'birth_date' => fake()->date(),

            'address' => fake()->address(),

            'identity_number' => fake()->numerify('################'),

            'marital_status' => fake()->randomElement([
                'Single',
                'Married',
            ]),

            'photo' => null,

            'emergency_contact_name' => fake()->name(),

            'emergency_contact_phone' => fake()->phoneNumber(),

            'is_active' => true,
        ];
    }
}