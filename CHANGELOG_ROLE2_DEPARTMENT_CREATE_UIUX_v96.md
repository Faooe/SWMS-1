# CHANGELOG ROLE 2 DEPARTMENT CREATE UI/UX v96

## Fokus
Redesign ulang halaman Role 2 > Department > Tambah Department agar lebih compact dan konsisten dengan form Role 2 terbaru.

## Perubahan
- Menghapus layout sidebar Status Department + Tips Master Data yang terlalu berat.
- Mengubah form menjadi satu section card utama yang lebih fokus.
- Header menggunakan hierarchy yang sama dengan Employee/Assignment form terbaru.
- Kode dan Nama Department memakai komponen input standar agar style konsisten.
- Deskripsi menggunakan textarea standard style: border tipis, rounded-2xl, focus biru.
- Status Department dipindahkan menjadi setting row compact di dalam card.
- Menambahkan badge Aktif/Nonaktif yang mengikuti toggle secara realtime.
- Action Batal/Simpan dipindahkan ke footer card, tidak lagi floating/sticky besar.
- Menambahkan error summary ringan saat validasi gagal.
- Responsive tetap satu kolom pada layar kecil.

## Tidak Diubah
- Controller
- Route
- Validation/business logic
- Database
- API

## File
- resources/views/department/create.blade.php
