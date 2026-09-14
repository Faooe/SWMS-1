# CHANGELOG Role 2 Department Create UI/UX v97

## Fokus
Menyelaraskan halaman Role 2 > Department > Tambah Department dengan pola form Role 2 terbaru seperti Employee dan Assignment.

## Perubahan
- Header mengikuti pola management form yang lebih sederhana dan compact.
- Menghapus badge/dekorasi yang membuat halaman terasa berbeda dari form Role 2 lain.
- Menggunakan satu section card utama dengan hierarchy yang sama seperti Employee.
- Input Kode dan Nama memakai komponen `x-ui.input` standar project.
- Deskripsi dipadatkan menjadi textarea 3 baris dengan style standar Role 2.
- Status Department dibuat sebagai setting row compact di dalam section, bukan panel terpisah.
- Action Batal/Tambah Department mengikuti sticky action bar seperti Employee.
- Error summary tetap dipertahankan.
- Tidak mengubah route, controller, validation, database, atau business logic Department.

## File
- resources/views/department/create.blade.php
