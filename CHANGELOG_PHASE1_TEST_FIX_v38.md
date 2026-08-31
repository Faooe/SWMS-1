# SWMS Backend v38 — Phase 1 test & middleware fix

- Menyatukan konfigurasi middleware global dan `trustProxies()` dalam satu `withMiddleware()`.
- Memperbaiki `RequestContext` / `SecurityHeaders` yang sebelumnya tertimpa callback middleware kedua.
- Menambahkan final exception-response hardening agar 401/403/404/422/429/500 tetap membawa `X-Request-ID` dan security headers.
- Mengubah default `ExampleTest` untuk menguji health endpoint `/up`, bukan root `/` yang memang boleh redirect.
- Tidak ada migration baru.
