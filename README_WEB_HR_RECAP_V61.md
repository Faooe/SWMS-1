# SWMS Backend v61 — Web Employee HR Recap

Perubahan:
- Employee Detail web: Performance lama diganti menjadi Rekap HR Employee.
- Filter: Hari Ini, Bulan, Rentang Bulan, Tahun.
- Attendance mengikuti Work Calendar company dan tidak menghitung future working days sebagai absent.
- Ringkasan attendance lengkap: working days, attended, present, late, leave, permission, absent, attendance rate, punctuality, work/late/early/overtime minutes.
- Ringkasan assignment lengkap: total, completed, in progress, approved, pending review, needs revision, rejected, not worked/expired, late revision, completion rate.
- Grafik Attendance dan Assignment terpisah.
- PDF dan Excel hanya Premium Go/Plus/Max, divalidasi di UI dan backend.
- Export memakai periode yang sama dengan layar.
- Excel 4 sheet: Rekap HR, Ringkasan Tren, Detail Attendance, Detail Assignment.
- Detail assignment export mencakup seluruh assignment yang relevan pada periode, bukan hanya completed.

Deployment:
1. Replace/deploy backend.
2. php artisan optimize:clear
3. Tidak ada migration baru.
