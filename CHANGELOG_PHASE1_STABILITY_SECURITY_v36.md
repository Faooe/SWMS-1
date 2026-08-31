# SWMS Backend v36 — Phase 1: Stability & Security

## Implemented
- Standard JSON API error envelope with `request_id`.
- Request correlation via `X-Request-ID`; timing, 5xx, and slow-request logs.
- Security response headers (nosniff, frame, referrer, permissions, HSTS on HTTPS).
- Login rate limiting by IP and identity; webhook rate limiting.
- Failed/successful login security logs.
- `POST /api/v1/logout-all` to revoke all API sessions.
- Password changes revoke all API tokens and FCM token.
- Stronger password requirements (8+, upper/lowercase, number).
- Automated feature/unit tests for API hardening, Midtrans probe, security headers, and response envelope.

## Deployment
No database migration. Recommended Vercel env: `LOG_CHANNEL=stderr`, `LOG_LEVEL=info`, `SLOW_REQUEST_MS=1500`.
