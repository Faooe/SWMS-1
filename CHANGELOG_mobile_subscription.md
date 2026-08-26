# Mobile Self-Service Subscription API

- GET /api/v1/subscription untuk Company Admin.
- POST /api/v1/subscription/checkout untuk membuat transaksi Midtrans.
- Harga/plan tetap bersumber dari config/plans.php yang sama dengan web.
- Callback pembayaran tetap memakai /api/v1/subscription/callback.
- MidtransService menerima optional finish URL agar checkout mobile punya landing page sendiri.
- Menambahkan public /subscription/mobile-finish setelah pembayaran dari aplikasi.
