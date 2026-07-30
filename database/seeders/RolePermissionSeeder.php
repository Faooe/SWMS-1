<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::where('code', 'SUPER_ADMIN')->first();

        if ($superAdmin) {
            $superAdmin->permissions()->sync(
                Permission::pluck('id')->toArray()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        $admin = Role::where('code', 'ADMIN')->first();

        if ($admin) {

            $permissions = Permission::whereNotIn('module', [
                'permission'
            ])->pluck('id')->toArray();

            $admin->permissions()->sync($permissions);
        }

        /*
        |--------------------------------------------------------------------------
        | SUPERVISOR
        |--------------------------------------------------------------------------
        */

        $supervisor = Role::where('code', 'SUPERVISOR')->first();

        if ($supervisor) {

            $permissions = Permission::whereIn('module', [

                'dashboard',

                'attendance',

                'assignment',

                'daily_report',

                'employee',

                'leave'

            ])->pluck('id')->toArray();

            $supervisor->permissions()->sync($permissions);
        }

        /*
        |--------------------------------------------------------------------------
        | EMPLOYEE
        |--------------------------------------------------------------------------
        */

        $employee = Role::where('code', 'EMPLOYEE')->first();

        if ($employee) {

            $permissions = Permission::whereIn('code', [

                'DASHBOARD_VIEW',

                'ATTENDANCE_VIEW',
                'ATTENDANCE_CREATE',

                'DAILY_REPORT_VIEW',
                'DAILY_REPORT_CREATE',
                'DAILY_REPORT_UPDATE',

                'ASSIGNMENT_VIEW',

                'LEAVE_VIEW',
                'LEAVE_CREATE',

            ])->pluck('id')->toArray();

            $employee->permissions()->sync($permissions);
        }
    }
}