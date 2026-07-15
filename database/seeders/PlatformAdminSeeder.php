<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PlatformAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::where(
            'code',
            'PLATFORM_ADMIN'
        )->first();

        if (!$role) {
            return;
        }

        User::updateOrCreate(

            [
                'email' => 'platform@swms.com',
            ],

            [

                'company_id' => null,

                'employee_id' => null,

                'role_id' => $role->id,

                'username' => 'platform',

                'password' => Hash::make('password'),

                'is_active' => true,

            ]

        );
    }
}