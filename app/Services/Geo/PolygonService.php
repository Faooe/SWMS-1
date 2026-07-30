<?php

namespace App\Services\Geo;

class PolygonService
{
    /*
    |--------------------------------------------------------------------------
    | Point In Polygon (Ray Casting Algorithm)
    |--------------------------------------------------------------------------
    | $polygon format: [[lat, lng], [lat, lng], ...]
    */
    public function isPointInPolygon(
        float $latitude,
        float $longitude,
        array $polygon
    ): bool {

        if (count($polygon) < 3) {
            return false;
        }

        $inside = false;
        $count = count($polygon);

        for ($i = 0, $j = $count - 1; $i < $count; $j = $i++) {

            $latI = $polygon[$i][0];
            $lngI = $polygon[$i][1];

            $latJ = $polygon[$j][0];
            $lngJ = $polygon[$j][1];

            // Rumus Ray Casting yang sudah diperbaiki
            $intersects = (($lngI > $longitude) !== ($lngJ > $longitude)) &&
                ($latitude < ($latJ - $latI) * ($longitude - $lngI) / ($lngJ - $lngI) + $latI);

            if ($intersects) {
                $inside = !$inside;
            }
        }

        return $inside;
    }

    /*
    |--------------------------------------------------------------------------
    | Polygon Centroid (untuk fallback distance display)
    |--------------------------------------------------------------------------
    */
    public function centroid(array $polygon): array
    {
        $latSum = 0;
        $lngSum = 0;
        $count = count($polygon);

        foreach ($polygon as $point) {
            $latSum += $point[0];
            $lngSum += $point[1];
        }

        return [
            $latSum / $count,
            $lngSum / $count,
        ];
    }
}