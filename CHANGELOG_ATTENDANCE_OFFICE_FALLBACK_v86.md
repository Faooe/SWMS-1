# Attendance Office Fallback v86

- Memperbaiki checkout Attendance pada record ASSIGNMENT ketika assignment context sudah tidak aktif/tersedia.
- Jika assignment valid: checkout boleh dari assignment atau Office.
- Jika assignment sudah tidak ada tetapi employee punya Office: checkout wajib berada di geofence Office.
- Tidak lagi mengizinkan checkout bebas saat assignment null.
- Tidak ada migration baru.
