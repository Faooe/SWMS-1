<?php

namespace App\Services;

use App\Models\SubscriptionPayment;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MidtransService
{
    /*
    |--------------------------------------------------------------------------
    | Midtrans Snap (Sandbox)
    |--------------------------------------------------------------------------
    |
    | Sengaja dibuat manual pakai Illuminate\Support\Facades\Http, TIDAK pakai
    | package "midtrans/midtrans-php" -- supaya tidak perlu composer require
    | tambahan. Cukup isi MIDTRANS_SERVER_KEY & MIDTRANS_CLIENT_KEY di .env
    | dengan Sandbox key dari dashboard.midtrans.com (mode Sandbox).
    |
    */

    protected function baseUrl(): string
    {
        return config('services.midtrans.is_production')
            ? 'https://app.midtrans.com/snap/v1'
            : 'https://app.sandbox.midtrans.com/snap/v1';
    }

    protected function serverKey(): string
    {
        $key = config('services.midtrans.server_key');

        if (blank($key)) {

            throw new RuntimeException(
                'MIDTRANS_SERVER_KEY belum diisi di .env.'
            );

        }

        return $key;

    }

    /*
    |--------------------------------------------------------------------------
    | Create Snap Transaction
    |--------------------------------------------------------------------------
    |
    | Return array berisi 'token' & 'redirect_url' dari Midtrans.
    | https://docs.midtrans.com/reference/snap-api-request
    |
    */

    public function createTransaction(
        SubscriptionPayment $payment,
        array $customerDetails = []
    ): array {

        $response = Http::withBasicAuth($this->serverKey(), '')

            ->acceptJson()

            ->post($this->baseUrl() . '/transactions', [

                'transaction_details' => [

                    'order_id' => $payment->order_id,

                    'gross_amount' => $payment->gross_amount,

                ],

                'item_details' => [
                    [

                        'id' => $payment->plan,

                        'price' => $payment->gross_amount,

                        'quantity' => 1,

                        'name' => "Subscription {$payment->plan} ({$payment->duration})",

                    ],
                ],

                'customer_details' => $customerDetails,

                'callbacks' => [

                    'finish' => route('subscription.finish'),

                ],

            ]);

        if ($response->failed()) {

            throw new RuntimeException(
                'Gagal membuat transaksi Midtrans: ' . $response->body()
            );

        }

        return $response->json();

    }

    /*
    |--------------------------------------------------------------------------
    | Verify Notification Signature
    |--------------------------------------------------------------------------
    |
    | https://docs.midtrans.com/docs/https-notification-webhooks
    | signature_key = SHA512(order_id + status_code + gross_amount + ServerKey)
    |
    */

    public function isValidSignature(array $payload): bool
    {
        $expected = hash('sha512',
            ($payload['order_id'] ?? '') .
            ($payload['status_code'] ?? '') .
            ($payload['gross_amount'] ?? '') .
            $this->serverKey()
        );

        return hash_equals(
            $expected,
            $payload['signature_key'] ?? ''
        );

    }
}
