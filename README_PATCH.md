# SWMS Backend v51 — Phase 3 Company Assignment UI/UX

Baseline: Backend v50.

Tidak ada migration baru.

Setelah deploy:
```bash
php artisan optimize:clear
php artisan view:clear
php artisan test
```

Catatan: package `vendor/` tidak tersedia pada environment build ChatGPT, sehingga full Laravel test suite perlu dijalankan pada environment project kamu.
