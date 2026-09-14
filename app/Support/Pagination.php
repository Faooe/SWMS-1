<?php

namespace App\Support;

final class Pagination
{
    public static function normalize(
        int|string|null $value,
        int $default = 10,
        int $max = 10,
        int $min = 1,
    ): int {
        $default = max($min, min($max, $default));
        $requested = is_numeric($value) ? (int) $value : $default;

        return max($min, min($max, $requested));
    }
}
