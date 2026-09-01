# Phase 3 — Attendance Daily Dedup Fix

## Masalah
Phase 3 Daily Assignment dapat menyimpan dua record pada employee + tanggal yang sama:
- OFFICE attendance
- ASSIGNMENT attendance

Keduanya dibutuhkan sebagai data teknis, terutama ASSIGNMENT untuk kalender attendance assignment. Namun Attendance Management, dashboard, statistik, history utama, dan performance tidak boleh menghitung employee dua kali dalam satu hari.

## Perbaikan
Menambahkan scope `Attendance::canonicalDaily()` yang mengambil hanya record terbaru per:
- company_id
- employee_id
- attendance_date

Scope ini hanya dipakai untuk rekap/list/history utama. Query kalender assignment tetap membaca record ASSIGNMENT secara langsung sehingga progress Daily Attendance tidak hilang.

## Dampak yang diharapkan
Jika employee punya OFFICE + ASSIGNMENT pada 01/09/2026:
- Database: kedua record tetap ada (data assignment tidak hilang)
- Kalender Assignment: tetap membaca ASSIGNMENT
- Attendance Management: employee hanya tampil 1x untuk 01/09/2026
- Dashboard attendance count: +1, bukan +2
- Statistik bulanan/analytics: +1 hari
- History employee utama: 1 record per tanggal
- Employee Performance attendance: 1 hari, bukan 2 record

## File
- app/Models/Attendance.php
- app/Services/AttendanceManagementService.php
- app/Services/DashboardService.php
- app/Services/EmployeeDashboardService.php
- app/Services/EmployeePerformanceService.php
- app/Services/AttendanceService.php
- app/Services/Attendance/AttendanceService.php

Tidak ada migration dan tidak menghapus data lama.
