<?php

namespace App\Services\Attendance;

class HaversineService
{
    /*
    |--------------------------------------------------------------------------
    | Earth Radius (meter)
    |--------------------------------------------------------------------------
    */

    private const EARTH_RADIUS = 6371000;

    /*
    |--------------------------------------------------------------------------
    | Distance
    |--------------------------------------------------------------------------
    */

    public function distance(
        float $latitudeFrom,
        float $longitudeFrom,
        float $latitudeTo,
        float $longitudeTo
    ): float {

        $latFrom = deg2rad($latitudeFrom);

        $latTo = deg2rad($latitudeTo);

        $deltaLat = deg2rad($latitudeTo - $latitudeFrom);

        $deltaLon = deg2rad($longitudeTo - $longitudeFrom);

        $a =
            sin($deltaLat / 2) ** 2 +
            cos($latFrom) * cos($latTo) * sin($deltaLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return self::EARTH_RADIUS * $c;

    }

    /*
    |--------------------------------------------------------------------------
    | Within Radius
    |--------------------------------------------------------------------------
    */

    public function isWithinRadius(
        float $distance,
        int $radius
    ): bool {

        return $distance <= $radius;

    }
}