<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\SubscriptionPayment;
use App\Services\CompanyService;
use Illuminate\Http\Request;

class PremiumController extends Controller
{
    public function __construct(
        protected CompanyService $companyService
    ) {
    }

    public function index()
    {
        $companies = Company::query()
            ->orderByDesc('subscription_plan')
            ->orderBy('name')
            ->paginate(15);

        // Summary dihitung dari SELURUH company, bukan hanya 15 item di page aktif.
        $summary = [
            'premium' => Company::query()->premium()->count(),
            'free' => Company::query()->where('subscription_plan', 'Free')->count(),
            'go' => Company::query()->where('subscription_plan', 'Premium Go')->count(),
            'plus' => Company::query()->where('subscription_plan', 'Premium Plus')->count(),
            'max' => Company::query()->where('subscription_plan', 'Premium Max')->count(),
            'expiring_soon' => Company::query()
                ->where('subscription_plan', '!=', 'Free')
                ->whereBetween('subscription_end', [today(), today()->addDays(7)])
                ->count(),
            'revenue_total' => (int) SubscriptionPayment::query()
                ->where('status', 'settlement')
                ->sum('gross_amount'),
            'revenue_month' => (int) SubscriptionPayment::query()
                ->where('status', 'settlement')
                ->whereYear('paid_at', now()->year)
                ->whereMonth('paid_at', now()->month)
                ->sum('gross_amount'),
            'settled_payments' => SubscriptionPayment::query()->where('status', 'settlement')->count(),
            'pending_payments' => SubscriptionPayment::query()->where('status', 'pending')->count(),
            'failed_payments' => SubscriptionPayment::query()->whereIn('status', ['failed', 'expired'])->count(),
        ];

        $payments = SubscriptionPayment::query()
            ->with('company:id,code,name,subscription_plan')
            ->latest('id')
            ->paginate(10, ['*'], 'payment_page');

        return view('platform.premium.index', [
            'companies' => $companies,
            'plans' => config('plans'),
            'summary' => $summary,
            'payments' => $payments,
        ]);
    }

    public function update(
        Request $request,
        Company $company
    ) {
        $request->validate([
            'plan' => ['required', 'in:Premium Go,Premium Plus,Premium Max'],
            'duration' => ['required', 'in:1_month,3_months,12_months'],
        ]);

        $this->companyService->updateSubscription(
            $company,
            $request->plan,
            $request->duration,
            'manual'
        );

        return redirect()
            ->route('platform.premium.index')
            ->with('success', 'Subscription berhasil diperbarui.');
    }

    public function cancel(Company $company)
    {
        $this->companyService->cancelSubscription($company);

        return redirect()
            ->route('platform.premium.index')
            ->with('success', 'Subscription dibatalkan, company dikembalikan ke Free plan.');
    }
}
