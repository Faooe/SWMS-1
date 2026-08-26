# Web UI v9 - Remove Duplicate Page Headings

Perubahan ini menghapus heading konten yang mengulang judul halaman yang sudah ditampilkan pada navbar/topbar.

## Cakupan
- Company Admin: Dashboard, Employee, Attendance, Leave/Permission, Office, Department (serta manager Position/Team bila diakses).
- Employee: My Assignment, Attendance History, Leave/Permission. Dashboard employee tetap mempertahankan greeting personal karena bukan duplikasi judul Dashboard.
- Platform Admin: Platform Dashboard, Companies, Premium, dan Profile.
- Company/Employee Profile: judul My Profile di konten dihapus, deskripsi tetap ditampilkan.

## Prinsip
- Judul utama halaman hanya tampil sekali di navbar/topbar.
- Subtitle/deskripsi halaman tetap dipertahankan.
- Heading section/card (mis. Attendance & Assignment Trend) tidak diubah.
- Tombol aksi tetap berada di posisi yang sesuai walaupun heading lokal dihapus.
