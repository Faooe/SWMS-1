# v85 - Assignment Session & Attendance Separation

- Menambahkan `work_check_in_at` dan `work_check_out_at` pada pivot assignment employee.
- Check Out Assignment non-Daily hanya menutup sesi assignment, tidak menutup attendance harian.
- Attendance harian tetap dibuka sampai employee Check Out dari menu Attendance.
- Attendance yang dimulai dari assignment dapat Check Out di geofence assignment atau office employee.
- Perhitungan work_minutes / early_leave / overtime pada API mobile diperbaiki saat Check Out.
- Informasi Assignment Role 2 menampilkan Check In/Out Assignment per employee.
- Tetap mempertahankan Daily Attendance assignment sebagai attendance per hari.
