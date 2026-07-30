<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPayment;
use App\Services\CompanyService;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    public function __construct(
        protected MidtransService $midtransService,
        protected CompanyService $companyService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Info Plan Saat Ini (dipakai oleh badge pojok kanan bawah)
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $company = Auth::user()->company;

        return view('subscription.index', [

            'company' => $company,

            'plans' => collect(config('plans'))
                ->except('Free'),

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Checkout -- Bikin Snap Token
    |--------------------------------------------------------------------------
    */

    public function checkout(Request $request)
    {
        $validated = $request->validate([

            'plan' => [
                'required',
                Rule::in([
                    'Premium Go',
                    'Premium Plus',
                    'Premium Max',
                ]),
            ],

            'duration' => [
                'required',
                Rule::in([
                    '1_month',
                    '3_months',
                    '12_months',
                ]),
            ],

        ]);

        $user = Auth::user();

        $company = $user->company;

        if (!$company) {

            return response()->json([
                'message' => 'Company tidak ditemukan.',
            ], 422);

        }

        $grossAmount = config(
            "plans.{$validated['plan']}.price.{$validated['duration']}"
        );

        if (!$grossAmount) {

            return response()->json([
                'message' => 'Harga plan/durasi tidak ditemukan.',
            ], 422);

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
            ]
        );

        $payment->update([
            'snap_token' => $result['token'] ?? null,
        ]);

        return response()->json([

            'snap_token' => $result['token'] ?? null,

            'client_key' => config('services.midtrans.client_key'),

            'is_production' => (bool) config('services.midtrans.is_production'),

        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Finish -- Redirect Setelah Popup Snap Ditutup (belum tentu sudah settled)
    |--------------------------------------------------------------------------
    */

    public function finish(Request $request)
    {
        return redirect()

            ->route('dashboard')

            ->with(

                'success',

                'Pembayaran sedang diproses. Plan kamu akan otomatis ter-upgrade setelah pembayaran dikonfirmasi.'

            );

    }

    /*
    |--------------------------------------------------------------------------
    | Callback -- Notifikasi Server-to-Server dari Midtrans
    |--------------------------------------------------------------------------
    |
    | Endpoint ini TIDAK dilindungi auth session (dipanggil oleh server
    | Midtrans, bukan browser user) -- keamanannya dari signature_key.
    | Daftarkan URL-nya sebagai "Payment Notification URL" di dashboard
    | Midtrans Sandbox.
    |
    */

    public function callback(Request $request)
    {
        $payload = $request->all();

        if (!$this->midtransService->isValidSignature($payload)) {

            Log::warning('Midtrans callback: invalid signature', $payload);

            return response()->json([
                'message' => 'Invalid signature.',
            ], 403);

        }

        $payment = SubscriptionPayment::where(
            'order_id',
            $payload['order_id'] ?? null
        )->first();

        if (!$payment) {

            return response()->json([
                'message' => 'Order not found.',
            ], 404);

        }

        $transactionStatus = $payload['transaction_status'] ?? null;

        $fraudStatus = $payload['fraud_status'] ?? null;

        $payment->update([

            'midtrans_transaction_id' => $payload['transaction_id'] ?? $payment->midtrans_transaction_id,

            'payment_type' => $payload['payment_type'] ?? $payment->payment_type,

            'callback_payload' => $payload,

        ]);

        /*
        |--------------------------------------------------------------------------
        | Sukses -> Upgrade Subscription
        |--------------------------------------------------------------------------
        */

        $isSuccess =
            $transactionStatus === 'settlement' ||
            ($transactionStatus === 'capture' && $fraudStatus === 'accept');

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

        } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny'])) {

            $payment->update([
                'status' => $transactionStatus === 'expire' ? 'expired' : 'failed',
            ]);

        }

        return response()->json([
            'message' => 'OK',
        ]);

    }
}
