<?php

namespace App\Support;

use App\Models\SubscriptionPayment;

final class SubscriptionPaymentData
{
    public static function make(SubscriptionPayment $payment, bool $includeCompany = false): array
    {
        $data = [
            'uuid' => $payment->uuid,
            'order_id' => $payment->order_id,
            'plan' => $payment->plan,
            'duration' => $payment->duration,
            'duration_label' => self::durationLabel($payment->duration),
            'gross_amount' => (int) $payment->gross_amount,
            'status' => $payment->status,
            'status_label' => self::statusLabel($payment->status),
            'payment_type' => $payment->payment_type,
            'midtrans_transaction_id' => $payment->midtrans_transaction_id,
            'paid_at' => optional($payment->paid_at)?->toIso8601String(),
            'created_at' => optional($payment->created_at)?->toIso8601String(),
            'updated_at' => optional($payment->updated_at)?->toIso8601String(),
        ];

        if ($includeCompany) {
            $data['company'] = $payment->company ? [
                'id' => $payment->company->id,
                'code' => $payment->company->code,
                'name' => $payment->company->name,
                'subscription_plan' => $payment->company->subscription_plan,
            ] : null;
        }

        return $data;
    }

    public static function durationLabel(?string $duration): string
    {
        return self::durationLabels()[$duration] ?? ($duration ?: '-');
    }

    /**
     * Canonical duration options shared by API responses and validation.
     * Keeping this list in one place prevents clients from seeing options that
     * checkout endpoints reject (or vice versa).
     *
     * @return list<array{key: string, label: string}>
     */
    public static function durationOptions(): array
    {
        return collect(self::durationLabels())
            ->map(fn (string $label, string $key): array => [
                'key' => $key,
                'label' => $label,
            ])
            ->values()
            ->all();
    }

    /** @return list<string> */
    public static function durationKeys(): array
    {
        return array_keys(self::durationLabels());
    }

    /** @return array<string, string> */
    private static function durationLabels(): array
    {
        return [
            '1_month' => '1 Bulan',
            '3_months' => '3 Bulan',
            '12_months' => '1 Tahun',
        ];
    }

    public static function statusLabel(?string $status): string
    {
        return match ($status) {
            'settlement' => 'Berhasil',
            'pending' => 'Menunggu Pembayaran',
            'expired' => 'Kedaluwarsa',
            'failed' => 'Gagal',
            default => $status ?: '-',
        };
    }
}
