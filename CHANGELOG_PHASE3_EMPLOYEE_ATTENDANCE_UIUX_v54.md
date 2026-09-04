# Phase 3 — Employee Attendance UI/UX v54

## Fokus
Penyempurnaan Role 3 (Employee) → Attendance Web agar konsisten dengan Dashboard, Assignment, Leave/Permission, dan Attendance History.

## Perubahan Web
- Header `Attendance Hari Ini` dibuat ringan tanpa card tambahan.
- Status hari ini membaca Office dan Assignment Attendance agar label tidak menyesatkan ketika Daily Assignment berjalan bersamaan dengan Office.
- Office Attendance dan Assignment Hari Ini digabung dalam satu `Workspace Attendance` surface.
- Office Attendance menampilkan Check In, Check Out, Jam Kerja, status, keterlambatan, jarak, radius, GPS, map, dan action dengan hierarchy yang lebih jelas.
- Raw latitude/longitude tidak lagi menjadi informasi utama; hanya ditampilkan kecil pada desktop.
- Warna amber pada Assignment Attendance dihilangkan dan disamakan dengan primary blue/netral SWMS.
- Assignment Hari Ini dibuat compact dan diarahkan ke My Assignment untuk Attendance Assignment.
- Ringkasan Bulan Ini diubah dari lima colorful cards menjadi satu surface dengan progress dan metric neutral.
- Wording Indonesia diseragamkan.

## Tidak Mengubah
- Logic Check In/Check Out Office.
- Logic Daily Assignment Attendance.
- Database schema.
- API contract.
