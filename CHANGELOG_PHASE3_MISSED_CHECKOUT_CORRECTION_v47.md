# Phase 3 v47 — Missed Check Out Correction

## Scope
Koreksi hanya untuk **lupa Check Out** pada Daily Attendance Assignment.
Lupa Check In tidak dapat dikoreksi secara retroaktif.

## Flow
1. Employee pernah Check In pada tanggal sebelumnya tetapi belum Check Out.
2. Kalender menampilkan `Belum Check Out` dan tombol `Ajukan Koreksi`.
3. Employee memilih jam pulang sebenarnya dan menulis alasan.
4. Company menerima notifikasi dan dapat Approve / Reject.
5. Approve mengisi Check Out attendance asli dan menghitung ulang work/early-leave/overtime.
6. Reject tidak mengubah attendance; employee dapat mengajukan ulang.

## Guard
- Hanya Daily Attendance Assignment.
- Hanya tanggal yang sudah lewat.
- Harus ada Check In asli.
- Attendance belum memiliki Check Out.
- Hanya satu request Pending per attendance.
- Jam yang diminta harus setelah Check In dan maksimal 23:00 di tanggal yang sama.
- Company hanya dapat mereview request milik company/assignment sendiri.
