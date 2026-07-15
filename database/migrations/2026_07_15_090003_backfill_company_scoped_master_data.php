<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Sebelum migration ini, data departments/positions/teams adalah
     * data dummy global (dari DepartmentSeeder/PositionSeeder/TeamSeeder)
     * yang dipakai bersama oleh semua company (company_id masih NULL).
     *
     * Migration ini menyalin data dummy tersebut menjadi milik masing-masing
     * company yang sudah ada, supaya company lama tetap punya Department,
     * Position, dan Team sendiri (tidak kosong), lalu merapikan referensi
     * di employment_histories supaya menunjuk ke data milik company yang benar.
     */
    public function up(): void
    {
        $companies = DB::table('companies')->select('id')->get();

        $templateDepartments = DB::table('departments')->whereNull('company_id')->get();
        $templatePositions = DB::table('positions')->whereNull('company_id')->get();
        $templateTeams = DB::table('teams')->whereNull('company_id')->get();

        foreach ($companies as $company) {

            // Lewati jika company ini sudah pernah dibackfill / sudah punya department sendiri.
            $alreadyScoped = DB::table('departments')
                ->where('company_id', $company->id)
                ->exists();

            if ($alreadyScoped) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Clone Departments
            |--------------------------------------------------------------------------
            */

            $departmentMap = [];

            foreach ($templateDepartments as $department) {

                $newId = DB::table('departments')->insertGetId([
                    'uuid' => (string) Str::uuid(),
                    'company_id' => $company->id,
                    'code' => $department->code,
                    'name' => $department->name,
                    'description' => $department->description,
                    'is_active' => $department->is_active,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $departmentMap[$department->id] = $newId;
            }

            /*
            |--------------------------------------------------------------------------
            | Clone Positions
            |--------------------------------------------------------------------------
            */

            $positionMap = [];

            foreach ($templatePositions as $position) {

                $newId = DB::table('positions')->insertGetId([
                    'uuid' => (string) Str::uuid(),
                    'company_id' => $company->id,
                    'code' => $position->code,
                    'name' => $position->name,
                    'description' => $position->description,
                    'is_active' => $position->is_active,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $positionMap[$position->id] = $newId;
            }

            /*
            |--------------------------------------------------------------------------
            | Clone Teams
            |--------------------------------------------------------------------------
            */

            $teamMap = [];

            foreach ($templateTeams as $team) {

                $newId = DB::table('teams')->insertGetId([
                    'uuid' => (string) Str::uuid(),
                    'company_id' => $company->id,
                    'code' => $team->code,
                    'name' => $team->name,
                    'department_id' => $departmentMap[$team->department_id] ?? $team->department_id,
                    'description' => $team->description,
                    'is_active' => $team->is_active,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $teamMap[$team->id] = $newId;
            }

            /*
            |--------------------------------------------------------------------------
            | Remap Employment Histories milik employee company ini
            |--------------------------------------------------------------------------
            */

            $employeeIds = DB::table('employees')
                ->where('company_id', $company->id)
                ->pluck('id');

            if ($employeeIds->isNotEmpty()) {

                $histories = DB::table('employment_histories')
                    ->whereIn('employee_id', $employeeIds)
                    ->get();

                foreach ($histories as $history) {

                    $update = [];

                    if ($history->department_id && isset($departmentMap[$history->department_id])) {
                        $update['department_id'] = $departmentMap[$history->department_id];
                    }

                    if ($history->position_id && isset($positionMap[$history->position_id])) {
                        $update['position_id'] = $positionMap[$history->position_id];
                    }

                    if ($history->team_id && isset($teamMap[$history->team_id])) {
                        $update['team_id'] = $teamMap[$history->team_id];
                    }

                    if (!empty($update)) {
                        DB::table('employment_histories')
                            ->where('id', $history->id)
                            ->update($update);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * Data hasil backfill (kloning) tidak dihapus otomatis saat rollback,
     * karena employment_histories sudah menunjuk ke data hasil kloning
     * tersebut. Hapus manual lewat database jika benar-benar diperlukan.
     */
    public function down(): void
    {
        // Intentionally left blank (data migration).
    }
};
