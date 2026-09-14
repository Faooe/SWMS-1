<?php

namespace App\Support;

use Illuminate\Validation\ValidationException;

/** Normalizes and validates polygon coordinates from web/mobile payloads. */
class PolygonDecoder
{
    public function decode(?string $polygon): ?array
    {
        if (empty($polygon)) {
            return null;
        }

        $decoded = json_decode($polygon, true);

        if (! is_array($decoded) || count($decoded) < 3) {
            throw ValidationException::withMessages([
                'polygon' => ['Polygon minimal memiliki 3 titik.'],
            ]);
        }

        $normalized = [];

        foreach ($decoded as $point) {
            if (is_array($point) && array_key_exists('lat', $point) && array_key_exists('lng', $point)) {
                $lat = $point['lat'];
                $lng = $point['lng'];
            } elseif (is_array($point) && array_key_exists(0, $point) && array_key_exists(1, $point)) {
                $lat = $point[0];
                $lng = $point[1];
            } else {
                throw ValidationException::withMessages([
                    'polygon' => ['Format titik polygon tidak valid.'],
                ]);
            }

            if (! is_numeric($lat) || ! is_numeric($lng)) {
                throw ValidationException::withMessages([
                    'polygon' => ['Koordinat polygon harus berupa angka.'],
                ]);
            }

            $lat = (float) $lat;
            $lng = (float) $lng;

            if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
                throw ValidationException::withMessages([
                    'polygon' => ['Koordinat polygon berada di luar batas yang valid.'],
                ]);
            }

            $normalized[] = [$lat, $lng];
        }

        return count($normalized) >= 3 ? $normalized : null;
    }
}
