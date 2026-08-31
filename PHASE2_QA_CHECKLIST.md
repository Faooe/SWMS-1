# SWMS Phase 2 QA Checklist — Subscription & SaaS

Use **Backend v39 + Mobile v32**.

## 0. Deployment

1. Deploy backend v39.
2. Run `php artisan migrate`.
3. Ensure Vercel env includes `CRON_SECRET` and Midtrans variables.
4. Run:
   ```bash
   php artisan optimize:clear
   php artisan test
   ```
5. Build/install Mobile v32.

## 1. Role 2 — Billing History

- Open Subscription & Billing.
- Current plan, employee usage, expiry and days remaining must appear.
- Previous Midtrans transactions should appear under Riwayat Pembayaran.
- A settled transaction should display Berhasil.
- A pending transaction should display Menunggu Pembayaran.
- Failed/expired transactions should not activate a plan.

## 2. Automatic Midtrans Upgrade

- Start with Premium Plus.
- Buy Premium Max in Sandbox.
- Complete payment until Midtrans status is settlement.
- Do not change plan manually from Role 1.
- Return to SWMS and refresh.
- Role 2 must show Premium Max automatically.
- Role 1 Premium/Billing must show the same transaction.


## 2B. Duplicate Webhook Safety

- After a Sandbox payment is already `settlement`, resend the same Midtrans notification from notification history if available.
- Expected: payment stays `settlement`.
- Subscription end date must **not** be extended a second time.
- There must still be only one purchased period for that order ID.

## 3. Same-plan Renewal

Example:

- Premium Plus currently ends 30 Sep.
- Buy Premium Plus 1 month before it expires.
- Expected: end date becomes around 30 Oct, not one month from payment date.
- Remaining paid days must not disappear.

## 4. Different-plan Upgrade

- Current plan: Premium Plus with remaining days.
- Buy Premium Max.
- Expected: Premium Max is active immediately after settlement and gets the purchased duration from activation time.

## 5. H-7 / H-3 / H-1 Reminder

For local QA, temporarily set a Premium company `subscription_end` to exactly 7 days from today and clear its reminder timestamp.
Run:

```bash
php artisan subscriptions:send-expiry-reminders
```

Expected:

- Company Admin receives one database notification (and FCM if token/config is ready).
- Running the command again does not duplicate the same H-7 reminder.
- Repeat for H-3 and H-1 if needed.

## 6. Automatic Expiry

Set a test Premium company end date to yesterday, then run:

```bash
php artisan subscriptions:downgrade-expired
```

Expected:

- Plan becomes Free.
- `subscription_end` becomes null.
- `max_employee` becomes Free plan limit.
- Existing employees remain in database.
- Premium-only features are locked.
- Company Admin + Platform Admin receive subscription-expired notification.

## 7. Employee Over-limit Safety

Use a company with more than 5 existing employees, then expire/downgrade it to Free.

Expected:

- All existing employees remain visible.
- No employee is deleted/deactivated automatically.
- Creating another employee is blocked by plan limit.
- Subscription screen shows over-limit warning.

## 8. Cron Endpoint

Call with correct bearer secret:

`GET /cron/subscriptions-downgrade`

Expected JSON contains:

- `reminder_output`
- `downgrade_output`

Wrong/no secret must return 401.

## 9. Role 1 — Premium & Billing

Check web and mobile:

- Plan management remains functional.
- Revenue month/total reflects only `settlement` Midtrans payments.
- Settled, pending, failed/expired counts are correct.
- Expiring-soon count is visible.
- Web dan mobile Billing Overview tidak overflow pada layar kecil / browser sempit.
- Recent payment list shows company, plan, amount and status.
- Manual Platform Admin plan changes do not create fake Midtrans revenue.

## 10. Regression

Re-test after Phase 2:

- Role 1/2/3 login.
- Employee CRUD and plan limit.
- Assignment workflow.
- Attendance / multi-day attendance.
- Leave/permission.
- Work Calendar.
- Notifications / FCM.
- Midtrans notification URL test.
- Phase 1 `php artisan test` remains green.
