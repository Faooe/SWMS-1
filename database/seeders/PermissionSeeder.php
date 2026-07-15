<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [

            [
                'module' => 'dashboard',
                'actions' => [
                    'view'
                ]
            ],

            [
                'module' => 'role',
                'actions' => [
                    'view',
                    'create',
                    'update',
                    'delete'
                ]
            ],

            [
                'module' => 'permission',
                'actions' => [
                    'view',
                    'create',
                    'update',
                    'delete'
                ]
            ],

            [
                'module' => 'employee',
                'actions' => [
                    'view',
                    'create',
                    'update',
                    'delete',
                    'export'
                ]
            ],

            [
                'module' => 'department',
                'actions' => [
                    'view',
                    'create',
                    'update',
                    'delete'
                ]
            ],

            [
                'module' => 'position',
                'actions' => [
                    'view',
                    'create',
                    'update',
                    'delete'
                ]
            ],

            [
                'module' => 'team',
                'actions' => [
                    'view',
                    'create',
                    'update',
                    'delete'
                ]
            ],

            [
                'module' => 'office',
                'actions' => [
                    'view',
                    'create',
                    'update',
                    'delete'
                ]
            ],

            [
                'module' => 'attendance',
                'actions' => [
                    'view',
                    'create',
                    'update',
                    'delete',
                    'approve',
                    'export'
                ]
            ],

            [
                'module' => 'assignment',
                'actions' => [
                    'view',
                    'create',
                    'update',
                    'delete',
                    'approve'
                ]
            ],

            [
                'module' => 'daily_report',
                'actions' => [
                    'view',
                    'create',
                    'update',
                    'approve',
                    'export'
                ]
            ],

            [
                'module' => 'leave',
                'actions' => [
                    'view',
                    'create',
                    'update',
                    'delete',
                    'approve'
                ]
            ],

            [
                'module' => 'notification',
                'actions' => [
                    'view',
                    'create',
                    'update',
                    'delete'
                ]
            ],

        ];

        foreach ($modules as $module) {

            foreach ($module['actions'] as $action) {

                Permission::updateOrCreate(

                    [
                        'code' => strtoupper($module['module'] . '_' . $action),
                    ],

                    [
                        'uuid' => (string) Str::uuid(),
                        'module' => $module['module'],
                        'action' => $action,
                        'code' => strtoupper($module['module'] . '_' . $action),
                        'name' => ucwords(str_replace('_', ' ', $module['module'])) . ' ' . ucfirst($action),
                        'description' => ucfirst($action) . ' permission for ' . $module['module'],
                        'is_active' => true,
                    ]

                );

            }

        }
    }
}