# v35 — Midtrans Callback Route Fix

- Memperbaiki route `GET|POST /api/v1/subscription/callback` agar menunjuk ke `App\\Http\\Controllers\\Api\\V1\\Subscription\\SubscriptionController::callback`.
- Sebelumnya route masih menunjuk ke Web `SubscriptionController::callback`, sehingga payload test Midtrans dengan `order_id=payment_notif_test_*` dibalas 404 `Order not found` dan dashboard Midtrans menampilkan `URL not found`.
- Callback API v34 yang test-compatible sekarang benar-benar digunakan:
  - test/connectivity payload -> HTTP 200, tidak mengubah database;
  - signature invalid -> HTTP 200 dan diabaikan;
  - unknown/dummy order -> HTTP 200 dan diabaikan;
  - transaksi asli + signature valid -> diproses dan subscription diperbarui.
- Tidak ada migration baru.
