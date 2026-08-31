<?php

namespace App\Support;

use InvalidArgumentException;

final class StrongPasswordGenerator
{
    private const UPPERCASE = 'ABCDEFGHJKMNPQRSTUVWXYZ';
    private const LOWERCASE = 'abcdefghjkmnpqrstuvwxyz';
    private const NUMBERS = '23456789';

    public static function generate(int $length = 12): string
    {
        if ($length < 8) {
            throw new InvalidArgumentException('Strong passwords must be at least 8 characters long.');
        }

        $characters = [
            self::randomCharacter(self::UPPERCASE),
            self::randomCharacter(self::LOWERCASE),
            self::randomCharacter(self::NUMBERS),
        ];

        $pool = self::UPPERCASE . self::LOWERCASE . self::NUMBERS;

        while (count($characters) < $length) {
            $characters[] = self::randomCharacter($pool);
        }

        // Fisher-Yates menggunakan random_int agar posisi karakter wajib
        // (uppercase/lowercase/number) tidak dapat diprediksi.
        for ($i = count($characters) - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            [$characters[$i], $characters[$j]] = [$characters[$j], $characters[$i]];
        }

        return implode('', $characters);
    }

    public static function meetsPolicy(string $password): bool
    {
        return strlen($password) >= 8
            && preg_match('/[A-Z]/', $password) === 1
            && preg_match('/[a-z]/', $password) === 1
            && preg_match('/[0-9]/', $password) === 1;
    }

    private static function randomCharacter(string $characters): string
    {
        return $characters[random_int(0, strlen($characters) - 1)];
    }
}
