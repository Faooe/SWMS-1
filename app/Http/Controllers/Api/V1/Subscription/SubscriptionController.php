<?php

namespace App\Http\Controllers\Api\V1\Subscription;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionPayment;
use App\Services\MidtransService;
use App\Services\CompanyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    public function __construct(
        protected MidtransService $midtransService,
        protected CompanyService $companyService
    ) {
    }

    public function show(Request $request): JsonResponse
    {
        $company = $request->user()?->company;

        if (!$company) {
            return ResponseHelper::error('Company tidak ditemukan.', null, 422);
        }

        $plans = collect(config('plans'))
            ->except('Free')
            ->map(fn (array $plan, string $key) => [
                'key' => $key,
                'label' => $plan['label'],
                'max_employee' => $plan['max_employee'],
                'price' => $plan['price'] ?? [],
            ])
            ->values();

        $latestPayment = $company->subscriptionPayments()
            ->latest('id')
            ->first();

        // Fallback recovery: bila webhook Midtrans sempat gagal/terlewat,
        // halaman subscription akan melakukan verifikasi server-to-server.
        if ($latestPayment?->isPending()) {
            try {
                $status = $this->midtransService->getTransactionStatus($latestPayment->order_id);
                $this->applyMidtransStatus($latestPayment, $status);
                $latestPayment->refresh();
                $company->refresh();
            } catch (\Throwable $e) {
                // Jangan membuat halaman subscription gagal hanya karena
                // Midtrans sedang tidak dapat dihubungi. Webhook tetap jalur utama.
                Log::warning('Midtrans reconcile gagal.', [
                    'order_id' => $latestPayment->order_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return ResponseHelper::success([
            'current' => [
                'plan' => $company->subscription_plan,
                'max_employee' => $company->max_employee,
                'subscription_start' => optional($company->subscription_start)->toDateString(),
                'subscription_end' => optional($company->subscription_end)->toDateString(),
                'is_premium' => $company->isPremium(),
            ],
            'plans' => $plans,
            'durations' => [
                ['key' => '1_month', 'label' => '1 Bulan'],
                ['key' => '3_months', 'label' => '3 Bulan'],
                ['key' => '12_months', 'label' => '1 Tahun'],
            ],
            'latest_payment' => $latestPayment ? [
                'order_id' => $latestPayment->order_id,
                'plan' => $latestPayment->plan,
                'duration' => $latestPayment->duration,
                'gross_amount' => $latestPayment->gross_amount,
                'status' => $latestPayment->status,
                'paid_at' => optional($latestPayment->paid_at)?->toIso8601String(),
                'created_at' => optional($latestPayment->created_at)?->toIso8601String(),
            ] : null,
        ], 'Data subscription berhasil diambil.');
    }

    public function checkout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'plan' => [
                'required',
                Rule::in(['Premium Go', 'Premium Plus', 'Premium Max']),
            ],
            'duration' => [
                'required',
                Rule::in(['1_month', '3_months', '12_months']),
            ],
        ]);

        $user = $request->user();
        $company = $user?->company;

        if (!$company) {
            return ResponseHelper::error('Company tidak ditemukan.', null, 422);
        }

        $grossAmount = config("plans.{$validated['plan']}.price.{$validated['duration']}");

        if (!$grossAmount) {
            return ResponseHelper::error('Harga plan/durasi tidak ditemukan.', null, 422);
        }

        $orderId = sprintf(
            'SUB-%s-%s',
            strtoupper($company->code),
            now()->format('YmdHis') . '-' . Str::random(5)
        );

        $payment = SubscriptionPayment::create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'order_id' => $orderId,
            'plan' => $validated['plan'],
            'duration' => $validated['duration'],
            'gross_amount' => $grossAmount,
            'status' => 'pending',
        ]);

        $result = $this->midtransService->createTransaction(
            $payment,
            [
                'first_name' => $company->name,
                'email' => $company->email ?? $user->email,
                'phone' => $company->phone,
            ],
            route('subscription.mobile-finish')
        );

        $payment->update([
            'snap_token' => $result['token'] ?? null,
        ]);

        return ResponseHelper::success([
            'order_id' => $orderId,
            'snap_token' => $result['token'] ?? null,
            'redirect_url' => $result['redirect_url'] ?? null,
            'client_key' => config('services.midtrans.client_key'),
            'is_production' => (bool) config('services.midtrans.is_production'),
            'gross_amount' => $grossAmount,
        ], 'Transaksi berhasil dibuat.');
    }

    /**
     * Public Midtrans payment notification endpoint.
     * Tidak memakai auth user; autentisitas diverifikasi dengan signature_key.
     */
    public function callback(Request $request): JsonResponse
    {
        $payload = $request->all();

        // Midtrans notification asli selalu membawa field transaksi berikut.
        // Request kosong/non-transaksi boleh dipakai sebagai connectivity test,
        // tetapi TIDAK pernah mengubah payment/subscription.
        $hasTransactionPayload = filled($payload['order_id'] ?? null)
            || filled($payload['transaction_status'] ?? null)
            || filled($payload['signature_key'] ?? null);

        if (!$hasTransactionPayload) {
            return response()->json([
                'message' => 'OK',
                'endpoint' => 'midtrans-notification',
            ], 200);
        }

        if (!$this->midtransService->isValidSignature($payload)) {
            Log::warning('Midtrans API callback: invalid signature.', [
                'order_id' => $payload['order_id'] ?? null,
                'transaction_status' => $payload['transaction_status'] ?? null,
            ]);

            return response()->json(['message' => 'Invalid signature.'], 403);
        }

        $payment = SubscriptionPayment::where('order_id', $payload['order_id'])->first();

        if (!$payment) {
            Log::warning('Midtrans API callback: order tidak ditemukan.', [
                'order_id' => $payload['order_id'] ?? null,
            ]);

            return response()->json(['message' => 'Order not found.'], 404);
        }

        $this->applyMidtransStatus($payment, $payload);

        // Midtrans menganggap 2xx sebagai notification berhasil diterima.
        return response()->json(['message' => 'OK'], 200);
    }

    private function applyMidtransStatus(SubscriptionPayment $payment, array $payload): void
    {
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        $payment->update([
            'midtrans_transaction_id' => $payload['transaction_id'] ?? $payment->midtrans_transaction_id,
            'payment_type' => $payload['payment_type'] ?? $payment->payment_type,
            'callback_payload' => $payload,
        ]);

        $isSuccess = $transactionStatus === 'settlement'
            || ($transactionStatus === 'capture' && in_array($fraudStatus, [null, 'accept'], true));

        if ($isSuccess && !$payment->isPaid()) {
            $payment->update([
                'status' => 'settlement',
                'paid_at' => now(),
            ]);

            $this->companyService->updateSubscription(
                $payment->company,
                $payment->plan,
                $payment->duration
            );

            Log::info('Subscription upgraded dari Midtrans.', [
                'order_id' => $payment->order_id,
                'company_id' => $payment->company_id,
                'plan' => $payment->plan,
                'source_status' => $transactionStatus,
            ]);
        } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny'], true)) {
            $payment->update([
                'status' => $transactionStatus === 'expire' ? 'expired' : 'failed',
            ]);
        }
    }

}
