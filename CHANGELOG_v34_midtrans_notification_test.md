# v34 — Midtrans Notification Test Compatibility

- Notification callback now accepts GET and POST for dashboard connectivity checks.
- GET/HEAD returns HTTP 200 without processing payment.
- Incomplete/test payloads return HTTP 200 but never mutate payment/subscription.
- Invalid signatures return HTTP 200 and are ignored; only valid signatures may update subscription.
- Unknown/dummy order IDs return HTTP 200 and are ignored.
- Real valid Midtrans notifications continue to be signature-verified and processed idempotently.
