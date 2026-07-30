<?php

namespace App\Services\Attendance;

use App\Models\Assignment;
use App\Models\Office;
use App\Services\Geo\PolygonService;

class AttendanceLocationService
{
    public function __construct(
        protected HaversineService $haversineService,
        protected PolygonService $polygonService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Office Location
    |--------------------------------------------------------------------------
    */

    public function validateOffice(
        Office $office,
        float $latitude,
        float $longitude
    ): array {

        $polygon = $office->polygon ?? null;

        if (!empty($polygon)) {

            return $this->validateWithPolygon(

                $polygon,

                $latitude,

                $longitude

            );

        }

        $distance = $this->haversineService->distance(

            (float) $office->latitude,

            (float) $office->longitude,

            $latitude,

            $longitude

        );

        return [

            'method' => 'radius',

            'distance' => round($distance, 2),

            'radius' => $office->radius,

            'allowed' => $this->haversineService->isWithinRadius(
                $distance,
                $office->radius
            ),

        ];

    }

    /*
    |--------------------------------------------------------------------------
    | Validate Assignment Location
    |--------------------------------------------------------------------------
    */

    public function validateAssignment(
        Assignment $assignment,
        float $latitude,
        float $longitude
    ): array {

        $polygon = $assignment->polygon ?? null;

        if (!empty($polygon)) {

            return $this->validateWithPolygon(

                $polygon,

                $latitude,

                $longitude

            );

        }

        $distance = $this->haversineService->distance(

            (float) $assignment->latitude,

            (float) $assignment->longitude,

            $latitude,

            $longitude

        );

        return [

            'method' => 'radius',

            'distance' => round($distance, 2),

            'radius' => $assignment->radius,

            'allowed' => $this->haversineService->isWithinRadius(
                $distance,
                $assignment->radius
            ),

        ];

    }

    /*
    |--------------------------------------------------------------------------
    | Validate With Polygon
    |--------------------------------------------------------------------------
    */

    private function validateWithPolygon(
        array $polygon,
        float $latitude,
        float $longitude
    ): array {

        $allowed = $this->polygonService->isPointInPolygon(

            $latitude,

            $longitude,

            $polygon

        );

        $centroid = $this->polygonService->centroid($polygon);

        $distance = $this->haversineService->distance(

            $centroid[0],

            $centroid[1],

            $latitude,

            $longitude

        );

        return [

            'method' => 'polygon',

            'distance' => round($distance, 2),

            'radius' => null,

            'allowed' => $allowed,

        ];

    }
}