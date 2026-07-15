<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        $shifts = [

            [
                'uuid' => (string) Str::uuid(),
                'code' => 'PAGI',
                'name' => 'Shift Pagi',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'break_start' => '12:00:00',
                'break_end' => '13:00:00',
                'late_tolerance' => 15,
                'work_days' => [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday'
                ],
                'is_night_shift' => false,
                'is_active' => true,
            ],

            [
                'uuid' => (string) Str::uuid(),
                'code' => 'SIANG',
                'name' => 'Shift Siang',
                'start_time' => '13:00:00',
                'end_time' => '21:00:00',
                'break_start' => '17:00:00',
                'break_end' => '18:00:00',
                'late_tolerance' => 15,
                'work_days' => [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday'
                ],
                'is_night_shift' => false,
                'is_active' => true,
            ],

            [
                'uuid' => (string) Str::uuid(),
                'code' => 'MALAM',
                'name' => 'Shift Malam',
                'start_time' => '20:00:00',
                'end_time' => '04:00:00',
                'break_start' => '00:00:00',
                'break_end' => '01:00:00',
                'late_tolerance' => 15,
                'work_days' => [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday'
                ],
                'is_night_shift' => true,
                'is_active' => true,
            ],

        ];

        foreach ($shifts as $shift) {

            Shift::updateOrCreate(
                ['code' => $shift['code']],
                $shift
            );

        }
    }
}