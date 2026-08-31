<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

final class SubscriptionPeriodCalculator
{
    public static function monthsForDuration(string $duration): int
    {
        return match ($duration) {
            '1_month' => 1,
            '3_months' => 3,
            '12_months' => 12,
            default => throw new \InvalidArgumentException('Durasi tidak dikenali.'),
        };
    }

    /**
     * Renewal plan yang sama tidak membuang sisa masa aktif: periode baru
     * diteruskan dari subscription_end saat ini. Ganti plan berlaku langsung
     * dari sekarang karena benefit/limit plan juga berubah saat itu.
     *
     * @return array{start: CarbonImmutable, end: CarbonImmutable, is_renewal: bool}
     */
    public static function calculate(
        string $currentPlan,
        ?CarbonInterface $currentStart,
        ?CarbonInterface $currentEnd,
        string $newPlan,
        string $duration,
        ?CarbonInterface $now = null,
    ): array {
        $months = self::monthsForDuration($duration);
        $now = CarbonImmutable::instance($now ?? now());
        $activeUntil = $currentEnd ? CarbonImmutable::instance($currentEnd) : null;

        $isRenewal = $currentPlan === $newPlan
            && $newPlan !== 'Free'
            && $activeUntil !== null
            && $activeUntil->endOfDay()->greaterThanOrEqualTo($now);

        if ($isRenewal) {
            return [
                'start' => $currentStart
                    ? CarbonImmutable::instance($currentStart)
                    : $now,
                'end' => $activeUntil->addMonthsNoOverflow($months),
                'is_renewal' => true,
            ];
        }

        return [
            'start' => $now,
            'end' => $now->addMonthsNoOverflow($months),
            'is_renewal' => false,
        ];
    }
}
