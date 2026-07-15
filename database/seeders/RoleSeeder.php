<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [

            [

                'code' => 'PLATFORM_ADMIN',

                'name' => 'Platform Administrator',

                'description' => 'Pengelola aplikasi SWMS',

            ],

            [

                'code' => 'SUPER_ADMIN',

                'name' => 'Company Administrator',

                'description' => 'Administrator perusahaan',

            ],

            [

                'code' => 'EMPLOYEE',

                'name' => 'Employee',

                'description' => 'Karyawan',

            ],

        ];

        foreach ($roles as $role) {

            Role::updateOrCreate(

                [

                    'code' => $role['code'],

                ],

                $role

            );

        }
    }
}