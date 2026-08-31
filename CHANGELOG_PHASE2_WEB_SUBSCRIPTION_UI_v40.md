# SWMS Backend v40 — Phase 2 Web Subscription UI

## Fix utama
- Menghapus modal subscription lama dari floating badge pada Company Admin.
- Floating badge sekarang membuka halaman `Subscription & Billing` penuh.
- Menambahkan menu `Subscription & Billing` pada sidebar Company Admin.
- Mendesain ulang halaman subscription agar konsisten dengan UI SWMS.

## UI Subscription Role 2
- Current plan card + status aktif/free.
- Employee usage dan progress limit.
- Tanggal berakhir dan sisa masa aktif.
- Lifecycle H-7/H-3/H-1 dan informasi auto-downgrade.
- Warning subscription hampir berakhir / employee over-limit.
- Plan cards Premium Go / Plus / Max dengan penanda plan aktif dan plan dipilih.
- Duration selector 1 bulan / 3 bulan / 1 tahun.
- Dynamic payment summary dan total harga.
- Midtrans checkout langsung dari halaman Subscription & Billing.
- Billing history dengan empty state dan status chips.
- Midtrans mode indicator Sandbox/Production mengikuti konfigurasi backend.

## Flow
- Setelah Snap success/pending, user kembali ke halaman Subscription & Billing agar status transaksi dapat dilihat langsung.
- Logic pembayaran, callback, lifecycle, renewal, dan migration Phase 2 v39 tetap dipertahankan.

## Migration
Tidak ada migration tambahan pada v40. Tetap gunakan migration Phase 2 dari v39.
