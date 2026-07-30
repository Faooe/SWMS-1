<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    /**
     * Default master data department.
     *
     * Data ini dipakai sebagai starter data setiap kali sebuah company
     * baru dibuat (lihat Database\Seeders\DepartmentSeeder::seedForCompany()),
     * bukan lagi sebagai data global yang dipakai bersama semua company.
     */
    public static function defaultData(): array
    {
        return [

            [
                'code' => 'IT',
                'name' => 'Information Technology',
                'description' => 'Manage information technology services.',
                'is_active' => true,
            ],

            [
                'code' => 'HR',
                'name' => 'Human Resources',
                'description' => 'Manage employees and recruitment.',
                'is_active' => true,
            ],

            [
                'code' => 'FIN',
                'name' => 'Finance',
                'description' => 'Manage company finances.',
                'is_active' => true,
            ],

            [
                'code' => 'OPS',
                'name' => 'Operations',
                'description' => 'Manage operational activities.',
                'is_active' => true,
            ],

            [
                'code' => 'MKT',
                'name' => 'Marketing',
                'description' => 'Manage marketing strategy.',
                'is_active' => true,
            ],

            [
                'code' => 'ADM',
                'name' => 'Administration',
                'description' => 'Company Administration',
                'is_active' => true,
            ],

        ];
    }

    /**
     * Run the database seeds.
     *
     * Tidak lagi dipanggil dari DatabaseSeeder karena Department sekarang
     * adalah data milik masing-masing company. Gunakan seedForCompany().
     */
    public function run(): void
    {
        //
    }

    /**
     * Seed default departments untuk satu company tertentu.
     *
     * @return array<string, int> code => department id
     */
    public static function seedForCompany(int $companyId): array
    {
        $map = [];

        foreach (self::defaultData() as $department) {

            $row = Department::updateOrCreate(
                [
                    'company_id' => $companyId,
                    'code' => $department['code'],
                ],
                [
                    'uuid' => (string) Str::uuid(),
                    'company_id' => $companyId,
                    'code' => $department['code'],
                    'name' => $department['name'],
                    'description' => $department['description'],
                    'is_active' => $department['is_active'],
                ]
            );

            $map[$department['code']] = $row->id;
        }

        return $map;
    }
}
