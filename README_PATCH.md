# SWMS Phase 3 — Daily Attendance Checkout & UI Consistency Patch

## Tujuan
Patch ini memperbaiki flow Daily Attendance Role 3 dan membuat UI web lebih konsisten dengan desain SWMS.

## Perubahan utama
1. Check In / Check Out Daily Attendance ditampilkan langsung pada card kalender, di web dan mobile.
2. Setelah Check In, tombol berubah menjadi **Check Out Hari Ini** setelah data reload.
3. `Selesai x/y` berarti jumlah hari wajib yang sudah Check Out.
4. Attendance hari lampau yang Check In tetapi tidak Check Out ditampilkan sebagai **Belum Check Out**, bukan `Sedang Bekerja`.
5. Hari attendance lama yang tidak lengkap tetap tercatat, tetapi tidak mengunci penyelesaian assignment selamanya. Pada hari terakhir, jika hari terakhir wajib attendance, employee harus Check Out hari terakhir sebelum Submit hasil.
6. UI summary web diubah ke palet netral + primary blue agar konsisten; warna pelangi pada metric cards dihilangkan.
7. Header Role 3 dibuat lebih konsisten (blue/slate untuk status/priority normal).
8. Wording Create/Edit Assignment diperjelas: toggle = **Aktifkan Attendance Harian**, rule = **Hari kerja company** atau **Setiap hari kalender**.
9. Mobile Daily Attendance menampilkan 4 metric: Kehadiran, Selesai, Jam Kerja, Tidak Hadir.

## Catatan untuk data lama
Jika sebuah tanggal sudah lewat dan employee sudah Check In tetapi tidak Check Out sebelum batas harian 23:00, tanggal itu akan tetap dihitung sebagai **Belum Check Out** dan `Selesai` tidak bertambah untuk hari tersebut. Patch tidak memalsukan waktu Check Out historis.

## Instalasi
Copy folder `backend` ke root project backend dan folder `mobile` ke root project mobile, lalu replace file dengan path yang sama.

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
