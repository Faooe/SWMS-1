# SWMS Backend v39 — Phase 2 Subscription & SaaS

Base: Backend v38 (Phase 1 complete).

## Implemented

- Billing history Company Admin (Role 2): status, plan, duration, amount, Midtrans method, order/transaction id, timestamps.
- Billing overview Platform Admin (Role 1): total/monthly settled revenue, settled/pending/failed payment count, subscriptions expiring <= 7 days, recent transactions. UI web dibuat konsisten dengan card/icon/status SWMS.
- API:
  - `GET /api/v1/subscription/history`
  - `GET /api/v1/platform/premium/payments`
- Existing `GET /api/v1/subscription` now also returns lifecycle state and latest 20 payments.
- H-7, H-3, H-1 subscription expiry notifications (database + FCM).
- Automatic downgrade to Free after subscription end.
- Lazy expiry safety-net: expired Premium is never considered active even when the cron has not run yet.
- Existing employee data is never deleted on downgrade. New employee creation remains blocked if existing count is already above the Free-plan limit.
- Same-plan renewal extends from the existing `subscription_end`, so remaining paid time is not lost.
- Changing to a different plan applies immediately and starts a new purchased period.
- Free plan no longer has a fake one-year expiry date; legacy Free rows are normalized by migration.
- Reminder state is reset after renewal/plan change.
- Midtrans settlement remains automatic, with no manual Platform Admin confirmation.
- Midtrans settlement processing is idempotent and row-locked, so duplicate/concurrent webhook deliveries cannot extend the same subscription twice.
- Platform manual plan changes remain supported and are explicitly not counted as payment/revenue.
- Role 2 web mendapat halaman Subscription & Billing dengan lifecycle card, warning expiry/over-limit, dan payment history responsive.
- Role 1 web Premium Management mendapat Billing Overview 6 metrik + payment history tanpa mengubah workflow plan-management lama.
- Production `APP_DEBUG` in `vercel.json` set to `false`.

## Migration

Run:

```bash
php artisan migrate
```

Migration added:

`2026_08_31_133000_add_subscription_lifecycle_tracking_to_companies_table.php`

Columns:

- `subscription_reminder_7_sent_at`
- `subscription_reminder_3_sent_at`
- `subscription_reminder_1_sent_at`
- `subscription_expired_at`

## Cron

Existing Vercel endpoint remains:

`GET /cron/subscriptions-downgrade`

It now performs both:

1. H-7/H-3/H-1 reminders.
2. Expired subscription downgrade.

`CRON_SECRET` must remain configured.

## Tests

Added:

- `SubscriptionPeriodCalculatorTest`
- `CompanyPremiumLifecycleTest`
- `PhaseTwoSubscriptionRoutesTest`
