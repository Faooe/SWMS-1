<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([

            /*
            |--------------------------------------------------------------------------
            | Master Data
            |--------------------------------------------------------------------------
            */

            RoleSeeder::class,

            PermissionSeeder::class,

            RolePermissionSeeder::class,

            /*
            |--------------------------------------------------------------------------
            | Department, Position, dan Team TIDAK di-seed di sini lagi.
            |--------------------------------------------------------------------------
            |
            | Ketiga data tersebut sekarang adalah master data milik masing-masing
            | company (punya kolom company_id), sehingga akan otomatis dibuatkan
            | starter data-nya sendiri oleh CompanyService setiap kali ada company
            | baru yang dibuat. Lihat DepartmentSeeder::seedForCompany(),
            | PositionSeeder::seedForCompany(), dan TeamSeeder::seedForCompany().
            |
            */

            ShiftSeeder::class,

            /*
            |--------------------------------------------------------------------------
            | Platform
            |--------------------------------------------------------------------------
            */

            PlatformAdminSeeder::class,

        ]);
    }
}