<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TeamSeeder extends Seeder
{
    /**
     * Default master data team, dikelompokkan berdasarkan kode department.
     *
     * Data ini dipakai sebagai starter data setiap kali sebuah company
     * baru dibuat (lihat Database\Seeders\TeamSeeder::seedForCompany()),
     * bukan lagi sebagai data global yang dipakai bersama semua company.
     */
    public static function defaultData(): array
    {
        return [

            // IT
            ['department_code' => 'IT', 'code' => 'BACKEND', 'name' => 'Backend Developer'],
            ['department_code' => 'IT', 'code' => 'FRONTEND', 'name' => 'Frontend Developer'],
            ['department_code' => 'IT', 'code' => 'MOBILE', 'name' => 'Mobile Developer'],
            ['department_code' => 'IT', 'code' => 'NETWORK', 'name' => 'Network Engineer'],

            // HR
            ['department_code' => 'HR', 'code' => 'RECRUITMENT', 'name' => 'Recruitment'],
            ['department_code' => 'HR', 'code' => 'TRAINING', 'name' => 'Training & Development'],

            // FINANCE
            ['department_code' => 'FIN', 'code' => 'ACCOUNTING', 'name' => 'Accounting'],
            ['department_code' => 'FIN', 'code' => 'PAYROLL', 'name' => 'Payroll'],

            // MARKETING
            ['department_code' => 'MKT', 'code' => 'DIGITAL', 'name' => 'Digital Marketing'],

            // OPERATION
            ['department_code' => 'OPS', 'code' => 'FIELD', 'name' => 'Field Operation'],

        ];
    }

    /**
     * Run the database seeds.
     *
     * Tidak lagi dipanggil dari DatabaseSeeder karena Team sekarang
     * adalah data milik masing-masing company. Gunakan seedForCompany().
     */
    public function run(): void
    {
        //
    }

    /**
     * Seed default teams untuk satu company tertentu.
     *
     * @param  array<string, int>  $departmentMap  code => department id, hasil dari DepartmentSeeder::seedForCompany()
     */
    public static function seedForCompany(int $companyId, array $departmentMap): void
    {
        foreach (self::defaultData() as $team) {

            $departmentId = $departmentMap[$team['department_code']] ?? null;

            if (!$departmentId) {
                continue;
            }

            Team::updateOrCreate(
                [
                    'company_id' => $companyId,
                    'code' => $team['code'],
                ],
                [
                    'uuid' => (string) Str::uuid(),
                    'company_id' => $companyId,
                    'department_id' => $departmentId,
                    'code' => $team['code'],
                    'name' => $team['name'],
                    'description' => $team['name'],
                    'is_active' => true,
                ]
            );
        }
    }
}
