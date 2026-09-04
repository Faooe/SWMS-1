# Phase 3 — Company Employee UI/UX v52

## Scope
Role 2 / Company Admin — Employee Management (Web + supporting API hardening).

## UI/UX
- Employee list menggunakan satu summary surface, bukan banyak stat-card.
- Filter konsisten: Search, Department, Office, Status.
- Table lebih compact dengan informasi NIP, email, department, office, position, dan employment type.
- Action list dibuat netral dan tidak penuh tombol warna-warni.
- Detail Employee dirapikan menjadi header, Informasi Pribadi, Pekerjaan & Penempatan, Akun & Login, lalu Performance.
- Performance summary tidak lagi berupa banyak colorful cards.
- Create/Edit diringkas menjadi tiga surface utama: Informasi Pribadi + Foto, Pekerjaan & Penempatan, Akun & Login.
- Emergency contact ditampilkan dan dapat diedit.
- Office dapat dipilih secara eksplisit; jika kosong saat create, Head Office tetap menjadi fallback.
- Employment status diselaraskan dengan backend: Active, Resigned, Retired, Suspended.

## Logic / integrity
- List mendukung filter Office dan per_page dibatasi maksimal 100.
- Sorting hanya menerima kolom yang diizinkan.
- Department, Position, Team, Office, dan Supervisor divalidasi harus milik company yang sama.
- Team harus sesuai Department.
- Employee tidak dapat menjadi supervisor dirinya sendiri.
- Edit tanpa field status tidak lagi otomatis mengaktifkan Employee/User.
- Toggle status menyinkronkan Employee dan akun User dalam satu transaction.
- EmployeeResource menambahkan emergency contact, company code, status akun, dan end date.
- Company relation di-eager-load untuk mencegah N+1 pada company_code.
- Web Employee Performance dan detail di-scope ulang ke company aktif.

## Migration
Tidak ada migration baru.
