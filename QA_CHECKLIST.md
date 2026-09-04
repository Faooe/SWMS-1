# Phase 3 — Employee Attendance UI/UX QA

Baseline: Backend v53 + Mobile v44.

## Web Role 3
1. Buka Attendance saat belum Check In → status `Belum Check In`, Office map tampil, tombol Check In disabled sampai GPS siap.
2. GPS di dalam radius → status lokasi `Di dalam area`, tombol Check In aktif.
3. Check In berhasil → waktu Check In dan status Hadir/Terlambat tampil setelah reload.
4. Setelah Check In → hanya action Check Out Office yang relevan tersedia.
5. Check Out berhasil → Jam Kerja terisi dan status selesai tampil.
6. Employee tanpa Office → empty state penempatan Office tampil dengan jelas.
7. Assignment aktif → panel Assignment Hari Ini tampil dan tombol membuka My Assignment.
8. Tidak ada Assignment → empty state compact, tidak ada warning berlebihan.
9. Ringkasan Bulan Ini menampilkan Hadir, Terlambat, Cuti/Izin, Tidak Hadir, dan attendance rate.
10. Riwayat Attendance tetap dapat dibuka.

## Mobile Role 3
1. General Attendance employee yang memiliki Office menunjukkan Office sebagai source dan hanya peta Office sebagai lokasi utama.
2. General Attendance field-worker tanpa Office menunjukkan Assignment aktif sebagai fallback target.
3. Sebelum Check In hanya satu primary action `Check In` tampil.
4. Sesudah Check In primary action berubah menjadi `Check Out`.
5. Sesudah Check Out panel berubah menjadi state `Attendance selesai hari ini`.
6. Riwayat Attendance dapat dibuka dari row pada panel utama.
7. Assignment aktif tampil sebagai section compact dengan `Buka My Assignment`.
8. Daily Assignment Attendance employee Office tetap dilakukan dari My Assignment dan tidak diubah oleh patch ini.
9. Pull-to-refresh tetap memuat today + context terbaru.
10. Jalankan `flutter analyze` dan targetkan `No issues found!`.

## Regression
- Leave/Permission tidak berubah.
- Attendance History tidak berubah.
- Assignment correction/review tidak berubah.
- Tidak ada migration baru.
