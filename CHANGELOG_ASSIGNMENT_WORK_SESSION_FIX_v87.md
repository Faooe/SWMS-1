# Assignment Work Session Fix v87

## Perbaikan
- Menambahkan `work_check_in_at` dan `work_check_out_at` ke fillable/casts `AssignmentEmployee`.
- Menambahkan kedua field ke `withPivot()` pada relasi Assignment <-> Employee.
- Employee dapat Check In ke lebih dari satu assignment pada hari yang sama; sesi assignment tidak lagi disembunyikan hanya karena attendance harian sudah tercatat.
- Jika attendance harian sudah berjalan, Check In assignment non-Daily hanya memvalidasi geofence dan membuka work session assignment tanpa membuat attendance harian kedua.
- Assignment non-Daily wajib Check In Assignment sebelum dapat diselesaikan.
- Submit/Selesaikan Assignment non-Daily otomatis menutup work session (`work_check_out_at`) tanpa menutup attendance harian.
- Role 2 Assignment Detail sekarang dapat membaca timestamp sesi assignment dari pivot dengan benar.

## Tidak berubah
- Attendance harian tetap terpisah dari sesi assignment.
- Daily Attendance Assignment mempertahankan workflow hariannya.
- Review/approval assignment tetap terpisah.
