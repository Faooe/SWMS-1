# Phase 3 — Company Employee UI/UX QA

## Deployment
Backend v52 + Mobile v43.

Backend:
```bash
php artisan optimize:clear
php artisan view:clear
php artisan test
```

Mobile:
```bash
flutter analyze
flutter run
```

## Web Role 2
1. Employee list: summary tampil sebagai satu panel, bukan 4 card terpisah.
2. Search nama/NIP/email bekerja.
3. Filter Department, Office, Active/Inactive bekerja dan Reset mengembalikan semua data.
4. Open Detail dan Edit dari tabel.
5. Toggle Active/Inactive harus mengubah status employee DAN akun login.
6. Detail menampilkan Personal, Employment/Office, Account, Emergency Contact, dan Performance.
7. Create/Edit: foto, emergency contact, office, team, supervisor, account tersimpan benar.
8. Team berbeda department harus ditolak backend.
9. ID Department/Position/Office/Team/Supervisor dari company lain harus ditolak.
10. Employee tidak boleh menjadi supervisor dirinya sendiri.
11. Performance employee company lain melalui manipulasi URL harus tidak dapat diakses.

## Mobile Role 2
1. Employee list memuat 30 data pertama dan load-more saat scroll mendekati bawah.
2. Company dengan >100 employee tetap dapat melihat seluruh employee secara bertahap.
3. Search mempertahankan pagination sesuai hasil pencarian.
4. Filter Department/Office/Status bekerja.
5. Buka form Assignment setelah melakukan filter Employee: daftar employee Assignment tidak boleh ikut terfilter.
6. Buka form Employee setelah search/filter: daftar Supervisor tetap berisi seluruh employee aktif yang valid.
7. Saat Edit employee nonaktif tanpa mengubah status, employee tidak boleh aktif kembali otomatis.
8. Activate/Deactivate dari Detail menyinkronkan akun login.
9. Emergency Contact muncul di Create/Edit dan Detail.
10. Setelah logout lalu login company lain, employee/supervisor/assignment picker tidak boleh menampilkan cache company lama.

## Regression
- Create Assignment dan employee selection tetap berjalan.
- Attendance/Leave/Assignment Role 3 tidak berubah.
- No new migration required.
