# CHANGELOG ROLE 2 ASSIGNMENT PAGINATION UIUX v88

## Fokus
Perapihan UI/UX pagination pada Role 2 > Assignment Management.

## Perubahan
- Mengganti pagination default Laravel dengan pagination custom yang konsisten dengan UI Role 2/3 terbaru.
- Tombol Previous/Next menjadi compact modern control.
- Menambahkan indikator page aktif.
- Menambahkan metadata jumlah data: Menampilkan x-y dari total assignment.
- Responsive: desktop menampilkan nomor halaman, mobile menampilkan format current/last.
- Disabled state lebih jelas.
- Tidak mengubah query, filter, sorting, route, API, atau business logic.

## File
- resources/views/components/assignment/table/table.blade.php
