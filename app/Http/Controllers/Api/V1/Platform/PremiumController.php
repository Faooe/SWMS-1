<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Platform\UpdateSubscriptionRequest;
use App\Http\Resources\Platform\CompanyResource;
use App\Models\Company;
use App\Models\SubscriptionPayment;
use App\Services\CompanyService;
use App\Support\SubscriptionPaymentData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Premium/subscription management untuk Platform Admin.
 */
class PremiumController extends Controller
{
    public function __construct(
        protected CompanyService $companyService
    ) {
    }

    /**
     * Ringkasan billing + riwayat transaksi Midtrans seluruh company.
     */
    public function payments(Request $request): JsonResponse
    {
        $query = SubscriptionPayment::query()
            ->with('company:id,code,name,subscription_plan');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('search')) {
            $search = trim($request->string('search')->toString());
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'ILIKE', "%{$search}%")
                    ->orWhereHas('company', function ($companyQuery) use ($search) {
                        $companyQuery->where('name', 'ILIKE', "%{$search}%")
                            ->orWhere('code', 'ILIKE', "%{$search}%");
                    });
            });
        }

        $perPage = min(max((int) $request->integer('per_page', 20), 5), 100);
        $payments = $query->latest('id')->paginate($perPage);

        $summary = [
            'settled_revenue_total' => (int) SubscriptionPayment::query()
                ->where('status', 'settlement')
                ->sum('gross_amount'),
            'settled_revenue_this_month' => (int) SubscriptionPayment::query()
                ->where('status', 'settlement')
                ->whereYear('paid_at', now()->year)
                ->whereMonth('paid_at', now()->month)
                ->sum('gross_amount'),
            'settled_payments' => SubscriptionPayment::query()->where('status', 'settlement')->count(),
            'pending_payments' => SubscriptionPayment::query()->where('status', 'pending')->count(),
            'failed_payments' => SubscriptionPayment::query()->whereIn('status', ['failed', 'expired'])->count(),
            'expiring_soon' => Company::query()
                ->where('subscription_plan', '!=', 'Free')
                ->whereBetween('subscription_end', [today(), today()->addDays(7)])
                ->count(),
        ];

        return ResponseHelper::success([
            'summary' => $summary,
            'items' => collect($payments->items())
                ->map(fn (SubscriptionPayment $payment) => SubscriptionPaymentData::make($payment, true))
                ->values(),
            'pagination' => [
                'current_page' => $payments->currentPage(),
                'last_page' => $payments->lastPage(),
                'per_page' => $payments->perPage(),
                'total' => $payments->total(),
            ],
        ], 'Data billing platform berhasil diambil.');
    }

    public function update(
        UpdateSubscriptionRequest $request,
        Company $company
    ): JsonResponse {
        $company = $this->companyService->updateSubscription(
            $company,
            $request->plan,
            $request->duration,
            'manual'
        );

        return ResponseHelper::success(
            new CompanyResource($company),
            'Subscription berhasil diperbarui.'
        );
    }

    public function cancel(Company $company): JsonResponse
    {
        $company = $this->companyService->cancelSubscription($company);

        return ResponseHelper::success(
            new CompanyResource($company),
            'Subscription dibatalkan, company dikembalikan ke Free plan.'
        );
    }
}
