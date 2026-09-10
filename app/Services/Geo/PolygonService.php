<?php

namespace App\Services\Geo;

class PolygonService
{
    /*
    |--------------------------------------------------------------------------
    | Point In Polygon (Ray Casting Algorithm)
    |--------------------------------------------------------------------------
    | Mendukung dua format yang pernah dipakai client SWMS:
    | [[lat, lng], ...] dan [{"lat": ..., "lng": ...}, ...].
    */
    public function isPointInPolygon(
        float $latitude,
        float $longitude,
        array $polygon
    ): bool {
        $points = $this->normalize($polygon);

        if (count($points) < 3) {
            return false;
        }

        $inside = false;
        $count = count($points);

        for ($i = 0, $j = $count - 1; $i < $count; $j = $i++) {
            [$latI, $lngI] = $points[$i];
            [$latJ, $lngJ] = $points[$j];

            $intersects = (($lngI > $longitude) !== ($lngJ > $longitude))
                && ($latitude < ($latJ - $latI) * ($longitude - $lngI) / ($lngJ - $lngI) + $latI);

            if ($intersects) {
                $inside = !$inside;
            }
        }

        return $inside;
    }

    /*
    |--------------------------------------------------------------------------
    | Polygon Centroid (untuk display distance)
    |--------------------------------------------------------------------------
    */
    public function centroid(array $polygon): array
    {
        $points = $this->normalize($polygon);

        if ($points === []) {
            return [0.0, 0.0];
        }

        $latSum = 0.0;
        $lngSum = 0.0;

        foreach ($points as [$lat, $lng]) {
            $latSum += $lat;
            $lngSum += $lng;
        }

        $count = count($points);

        return [$latSum / $count, $lngSum / $count];
    }

    private function normalize(array $polygon): array
    {
        $normalized = [];

        foreach ($polygon as $point) {
            if (!is_array($point)) {
                continue;
            }

            if (array_key_exists('lat', $point) && array_key_exists('lng', $point)) {
                $lat = $point['lat'];
                $lng = $point['lng'];
            } elseif (array_key_exists(0, $point) && array_key_exists(1, $point)) {
                $lat = $point[0];
                $lng = $point[1];
            } else {
                continue;
            }

            if (!is_numeric($lat) || !is_numeric($lng)) {
                continue;
            }

            $normalized[] = [(float) $lat, (float) $lng];
        }

        return $normalized;
    }
}
