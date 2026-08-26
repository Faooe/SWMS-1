<?php

namespace App\Http\Controllers\Api\V1\Subscription;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionPayment;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    public function __construct(
        protected MidtransService $midtransService
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
}
