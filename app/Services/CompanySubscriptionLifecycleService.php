<?php

namespace App\Services;

use App\Models\Company;
use App\Models\User;
use App\Notifications\SubscriptionChanged;
use App\Notifications\SubscriptionExpiryReminder;
use App\Support\SubscriptionPeriodCalculator;
use Illuminate\Support\Facades\Notification;

class CompanySubscriptionLifecycleService
{
    /*
    |--------------------------------------------------------------------------
    | Update Subscription
    |--------------------------------------------------------------------------
    |
    | Kalau company memperpanjang plan YANG SAMA sebelum masa aktif habis,
    | durasi baru ditambahkan dari subscription_end lama supaya sisa hari tidak
    | hilang. Kalau ganti plan, plan baru berlaku langsung dari sekarang.
    |
    */

    public function updateSubscription(
        Company $company,
        string $plan,
        string $duration,
        string $reason = 'manual'
    ): Company {
        $plans = config('plans');

        if (! isset($plans[$plan])) {
            throw new \InvalidArgumentException('Plan tidak dikenali.');
        }

        $oldPlan = (string) $company->subscription_plan;
        $period = SubscriptionPeriodCalculator::calculate(
            $oldPlan,
            $company->subscription_start,
            $company->subscription_end,
            $plan,
            $duration,
            now(),
        );

        $company->update([
            'subscription_plan' => $plan,
            'subscription_start' => $period['start'],
            'subscription_end' => $period['end'],
            'max_employee' => $plans[$plan]['max_employee'],
            'subscription_reminder_7_sent_at' => null,
            'subscription_reminder_3_sent_at' => null,
            'subscription_reminder_1_sent_at' => null,
            'subscription_expired_at' => null,
        ]);

        $fresh = $company->fresh();
        $notificationReason = $period['is_renewal'] ? 'renewed' : $reason;
        $this->notifySubscriptionChanged($fresh, $oldPlan, $plan, $notificationReason);

        return $fresh;
    }

    /*
    |--------------------------------------------------------------------------
    | Cancel Subscription (Revert to Free)
    |--------------------------------------------------------------------------
    */

    public function cancelSubscription(
        Company $company
    ): Company {
        $plans = config('plans');
        $oldPlan = (string) $company->subscription_plan;

        $company->update([
            'subscription_plan' => 'Free',
            'subscription_start' => now(),
            'subscription_end' => null,
            'max_employee' => $plans['Free']['max_employee'],
            'subscription_reminder_7_sent_at' => null,
            'subscription_reminder_3_sent_at' => null,
            'subscription_reminder_1_sent_at' => null,
            'subscription_expired_at' => null,
        ]);

        $fresh = $company->fresh();
        $this->notifySubscriptionChanged($fresh, $oldPlan, 'Free', 'cancelled');

        return $fresh;
    }

    /*
    |--------------------------------------------------------------------------
    | Subscription Expiry Reminder H-7 / H-3 / H-1
    |--------------------------------------------------------------------------
    */

    public function sendSubscriptionExpiryReminders(): int
    {
        $sent = 0;

        foreach ([7, 3, 1] as $days) {
            $column = "subscription_reminder_{$days}_sent_at";
            $targetDate = today()->addDays($days)->toDateString();

            $companies = Company::query()
                ->where('subscription_plan', '!=', 'Free')
                ->whereNotNull('subscription_end')
                ->whereDate('subscription_end', $targetDate)
                ->whereNull($column)
                ->get();

            foreach ($companies as $company) {
                $admins = User::query()->companyAdminsOf($company->id)->get();

                if ($admins->isNotEmpty()) {
                    Notification::send(
                        $admins,
                        new SubscriptionExpiryReminder($company, $days)
                    );
                }

                // Tandai tetap terkirim walaupun company belum punya token FCM;
                // database notification tetap disimpan dan FcmChannel bersifat optional.
                $company->forceFill([$column => now()])->save();
                $sent++;
            }
        }

        return $sent;
    }

    /*
    |--------------------------------------------------------------------------
    | Downgrade Expired Subscriptions
    |--------------------------------------------------------------------------
    |
    | Data employee TIDAK dihapus saat downgrade. Hanya limit plan berubah ke
    | Free; EmployeeService sudah mencegah penambahan employee baru bila jumlah
    | existing >= max_employee.
    |
    */

    public function downgradeExpiredSubscriptions(): int
    {
        $expired = Company::query()
            ->where('subscription_plan', '!=', 'Free')
            ->whereNotNull('subscription_end')
            ->whereDate('subscription_end', '<', today())
            ->get();

        foreach ($expired as $company) {
            $this->downgradeIfExpired($company);
        }

        return $expired->count();
    }

    /**
     * Lazy safety-net untuk satu company. Berguna kalau cron serverless
     * terlambat: begitu halaman subscription dibuka, status ikut dirapikan.
     */
    public function downgradeIfExpired(Company $company): Company
    {
        if (
            $company->subscription_plan === 'Free'
            || $company->subscription_end === null
            || $company->subscription_end->endOfDay()->greaterThanOrEqualTo(now())
        ) {
            return $company;
        }

        $plans = config('plans');
        $oldPlan = (string) $company->subscription_plan;
        $expiredAt = $company->subscription_end->endOfDay();

        $company->update([
            'subscription_plan' => 'Free',
            'subscription_start' => now(),
            'subscription_end' => null,
            'max_employee' => $plans['Free']['max_employee'],
            'subscription_expired_at' => $expiredAt,
            'subscription_reminder_7_sent_at' => null,
            'subscription_reminder_3_sent_at' => null,
            'subscription_reminder_1_sent_at' => null,
        ]);

        $fresh = $company->fresh();
        $this->notifySubscriptionChanged($fresh, $oldPlan, 'Free', 'expired');

        return $fresh;
    }

    /**
     * Ringkasan lifecycle yang dipakai web & mobile.
     */
    public function subscriptionLifecycle(Company $company): array
    {
        $employeeCount = $company->employees()->count();
        $end = $company->subscription_end;
        $daysRemaining = null;

        if ($company->isPremium() && $end) {
            $daysRemaining = (int) max(0, today()->diffInDays($end, false));
        }

        return [
            'days_remaining' => $daysRemaining,
            'is_expiring_soon' => $daysRemaining !== null && $daysRemaining <= 7,
            'is_expired' => $company->subscription_plan !== 'Free' && ! $company->isPremium(),
            'employee_count' => $employeeCount,
            'employee_limit' => (int) $company->max_employee,
            'over_employee_limit' => $employeeCount > (int) $company->max_employee,
            'expired_at' => optional($company->subscription_expired_at)?->toIso8601String(),
        ];
    }

    private function notifySubscriptionChanged(Company $company, string $oldPlan, string $newPlan, string $reason): void
    {
        if ($oldPlan === $newPlan && ! in_array($reason, ['renewed', 'payment'], true)) {
            return;
        }

        $companyAdmins = User::query()->companyAdminsOf($company->id)->get();
        $platformAdmins = User::query()
            ->where('is_active', true)
            ->whereHas('role', fn ($q) => $q->where('code', 'PLATFORM_ADMIN'))
            ->get();

        Notification::send(
            $companyAdmins->concat($platformAdmins)->unique('id')->values(),
            new SubscriptionChanged($company, $oldPlan, $newPlan, $reason)
        );
    }
}
