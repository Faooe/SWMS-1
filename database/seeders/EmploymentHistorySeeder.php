<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmploymentHistory;
use App\Models\Office;
use App\Models\Position;
use App\Models\Shift;
use App\Models\Team;
use Illuminate\Database\Seeder;

class EmploymentHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = Employee::where(
            'employee_number',
            'EMP-2026-0001'
        )->first();

        $department = Department::where(
            'code',
            'IT'
        )->first();

        $position = Position::where(
            'code',
            'MGR'
        )->first();

        $team = Team::where(
            'code',
            'BACKEND'
        )->first();

        $office = Office::where(
            'code',
            'HQ'
        )->first();

        $shift = Shift::where(
            'code',
            'PAGI'
        )->first();

        if (
            !$employee ||
            !$department ||
            !$position ||
            !$team ||
            !$office ||
            !$shift
        ) {
            return;
        }

        EmploymentHistory::updateOrCreate(

            [
                'employee_id' => $employee->id,
                'is_current' => true,
            ],

            [

                'department_id' => $department->id,

                'position_id' => $position->id,

                'team_id' => $team->id,

                'office_id' => $office->id,

                'shift_id' => $shift->id,

                'supervisor_id' => null,

                'employment_type' => 'Permanent',

                'employment_status' => 'Active',

                'start_date' => now()->subYear(),

                'end_date' => null,

                'is_current' => true,

            ]

        );
    }
}