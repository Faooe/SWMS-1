<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PositionSeeder extends Seeder
{
    /**
     * Default master data position.
     *
     * Data ini dipakai sebagai starter data setiap kali sebuah company
     * baru dibuat (lihat Database\Seeders\PositionSeeder::seedForCompany()),
     * bukan lagi sebagai data global yang dipakai bersama semua company.
     */
    public static function defaultData(): array
    {
        return [

            [
                'code' => 'DIR',
                'name' => 'Director',
                'description' => 'Top level executive.',
                'is_active' => true,
            ],

            [
                'code' => 'MGR',
                'name' => 'Manager',
                'description' => 'Department manager.',
                'is_active' => true,
            ],

            [
                'code' => 'SPV',
                'name' => 'Supervisor',
                'description' => 'Team supervisor.',
                'is_active' => true,
            ],

            [
                'code' => 'STF',
                'name' => 'Staff',
                'description' => 'Operational staff.',
                'is_active' => true,
            ],

            [
                'code' => 'INT',
                'name' => 'Intern',
                'description' => 'Internship employee.',
                'is_active' => true,
            ],

            [
                'code' => 'ADM',
                'name' => 'Administrator',
                'description' => 'Company Administrator',
                'is_active' => true,
            ],

        ];
    }

    /**
     * Run the database seeds.
     *
     * Tidak lagi dipanggil dari DatabaseSeeder karena Position sekarang
     * adalah data milik masing-masing company. Gunakan seedForCompany().
     */
    public function run(): void
    {
        //
    }

    /**
     * Seed default positions untuk satu company tertentu.
     *
     * @return array<string, int> code => position id
     */
    public static function seedForCompany(int $companyId): array
    {
        $map = [];

        foreach (self::defaultData() as $position) {

            $row = Position::updateOrCreate(
                [
                    'company_id' => $companyId,
                    'code' => $position['code'],
                ],
                [
                    'uuid' => (string) Str::uuid(),
                    'company_id' => $companyId,
                    'code' => $position['code'],
                    'name' => $position['name'],
                    'description' => $position['description'],
                    'is_active' => $position['is_active'],
                ]
            );

            $map[$position['code']] = $row->id;
        }

        return $map;
    }
}
