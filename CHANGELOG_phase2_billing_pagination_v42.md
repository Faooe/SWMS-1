# Phase 2 Billing Pagination v42

- Riwayat pembayaran web Role 2 dibatasi 10 transaksi per halaman dengan tombol Sebelumnya/Selanjutnya.
- Riwayat pembayaran web Role 1 dibatasi 10 transaksi per halaman dengan tombol Sebelumnya/Selanjutnya.
- API `/api/v1/subscription/history` default dan maksimum 10 item per halaman.
- API `/api/v1/platform/premium/payments` default dan maksimum 10 item per halaman.
- Mobile Role 2 kini mengambil riwayat pembayaran secara paginated (10 per halaman) dan menyediakan navigasi halaman.
- Mobile Role 1 Billing Overview kini menampilkan maksimum 10 transaksi per halaman dan menyediakan navigasi halaman.
- Tidak ada migration database baru.
