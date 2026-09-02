# Phase 3 — Employee Dashboard UI/UX v49

## Fokus
Penyederhanaan Dashboard Role 3 (Employee) di Web agar lebih fokus pada aktivitas harian dan tidak terasa sebagai kumpulan card statistik.

## Perubahan
- Greeting besar ber-gradient diganti header ringan dengan avatar, tanggal, dan status hari ini.
- Attendance + Assignment hari ini digabung ke satu `Ringkasan Aktivitas` surface.
- Statistik assignment 8 card dipadatkan menjadi satu `Ringkasan Assignment` dengan progress dan 4 metric utama.
- Assignment hari ini ditampilkan sebagai compact list row, maksimal 3 item pada dashboard.
- Recent Activities dirapikan sebagai satu list timeline dengan label aktivitas yang lebih manusiawi.
- Quick Actions diubah dari 4 card warna-warni menjadi navigation list dengan satu bahasa visual primary-blue.
- Warna semantik hanya dipakai untuk kondisi yang memang membutuhkan perhatian seperti Needs Revision.

## Tidak mengubah
- Workflow Assignment / Daily Attendance.
- Leave / Permission logic.
- Attendance logic.
- Database schema.
