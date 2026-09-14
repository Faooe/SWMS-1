# CHANGELOG ROLE 2 DEPARTMENT CREATE UI/UX v95

## Fokus
Perapihan UI/UX Role 2 > Department > Tambah Department agar konsisten dengan form Role 2 terbaru.

## Perubahan
- Header form dibuat lebih compact dan hierarchy judul/subtitle diperjelas.
- Layout desktop memakai informasi utama + panel status/tips agar ruang halaman lebih seimbang.
- Field Kode, Nama, dan Deskripsi memakai border tipis, background lembut, tanpa shadow field berat, dan focus ring biru.
- Menambahkan helper text pada field penting.
- Status Department diubah menjadi setting card dengan toggle modern.
- Menambahkan tips master data yang ringan.
- Action Batal/Simpan Department menggunakan sticky action bar yang konsisten dengan form Employee.
- Responsive mobile web tetap satu kolom.

## Tidak berubah
- Route
- Controller
- Validation
- Database
- Business logic Department

## File
- resources/views/department/create.blade.php
