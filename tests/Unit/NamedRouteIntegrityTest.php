<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class NamedRouteIntegrityTest extends TestCase
{
    /**
     * Critical navigation targets used by the role 2 configuration screens.
     * Keeping this list close to the route contract prevents a renamed route
     * from surfacing later as a 500 while rendering a Blade view.
     */
    public function test_role_two_configuration_routes_remain_registered(): void
    {
        foreach ([
            'assignments.index',
            'assignment-settings.edit',
            'assignment-settings.update',
            'attendance.calendar',
            'attendance.calendar.schedule',
            'attendance.calendar.holidays.store',
            'attendance.calendar.holidays.edit',
            'attendance.calendar.holidays.update',
            'attendance.calendar.holidays.destroy',
        ] as $name) {
            self::assertTrue(Route::has($name), "Named route [{$name}] is missing.");
        }
    }
}
