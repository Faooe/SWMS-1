<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offices = [

            [
                'uuid' => (string) Str::uuid(),
                'code' => 'HQ',
                'name' => 'Kantor Pusat',
                'address' => 'Jl. Ahmad Yani KM 35, Banjarbaru',
                'city' => 'Banjarbaru',
                'province' => 'Kalimantan Selatan',
                'postal_code' => '70714',
                'latitude' => -3.4427000,
                'longitude' => 114.8300000,
                'radius' => 200,
                'timezone' => 'Asia/Makassar',
                'is_head_office' => true,
                'is_active' => true,
            ],

            [
                'uuid' => (string) Str::uuid(),
                'code' => 'BJM',
                'name' => 'Kantor Cabang Banjarmasin',
                'address' => 'Jl. A. Yani KM 5, Banjarmasin',
                'city' => 'Banjarmasin',
                'province' => 'Kalimantan Selatan',
                'postal_code' => '70234',
                'latitude' => -3.3186000,
                'longitude' => 114.5944000,
                'radius' => 200,
                'timezone' => 'Asia/Makassar',
                'is_head_office' => false,
                'is_active' => true,
            ],

        ];

        foreach ($offices as $office) {

            Office::updateOrCreate(
                ['code' => $office['code']],
                $office
            );

        }
    }
}