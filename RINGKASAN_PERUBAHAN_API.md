# Ringkasan Perubahan API — SWMS (untuk Flutter)

Cara pakai: extract zip ini di root project Laravel kamu, timpa (replace)
file yang sudah ada. Struktur folder di zip ini sama persis dengan
struktur project, jadi tinggal drag & drop / overwrite.

Setelah replace, jalankan (kalau pakai Laravel Octane/cache):
```
php artisan route:clear
php artisan config:clear
php artisan optimize:clear
```

## 1. Bug yang diperbaiki

- **`app/Http/Resources/AssignmentResource.php`** — sebelumnya field
  `code`, `start_date`, `end_date` dipakai padahal TIDAK ADA di model
  `Assignment` (kolom aslinya `assignment_number`, `start_datetime`,
  `end_datetime`), jadi selalu `null` di response API. Sudah
  diperbaiki total + ditambah `priority`, `assignment_type`, daftar
  employee, dan `my_status` / `my_actions` (status assignment khusus
  untuk employee yang login, dipakai untuk munculkan tombol
  Accept/Reject/Check In/Check Out/Complete di Flutter).

- **`GET /v1/dashboard`** — sebelumnya SELALU mengembalikan data
  dashboard company admin untuk role apapun (termasuk EMPLOYEE).
  Sekarang bercabang: role EMPLOYEE dapat dashboard pribadi (assignment
  & absensi miliknya), role lain tetap dashboard company seperti biasa.

## 2. Endpoint baru — Employee

| Method | Endpoint | Fungsi |
|---|---|---|
| GET | `/v1/my-assignments` | Daftar assignment milik sendiri |
| GET | `/v1/my-assignments/today` | Assignment hari ini |
| GET | `/v1/my-assignments/statistics` | Statistik assignment |
| GET | `/v1/my-assignments/{uuid}` | Detail assignment |
| POST | `/v1/my-assignments/{uuid}/accept` | Terima assignment |
| POST | `/v1/my-assignments/{uuid}/reject` | Tolak assignment |
| POST | `/v1/my-assignments/{uuid}/check-in` | Check in (kirim `latitude`, `longitude`) |
| POST | `/v1/my-assignments/{uuid}/check-out` | Check out (kirim `latitude`, `longitude`) |
| POST | `/v1/my-assignments/{uuid}/complete` | Selesaikan (kirim file `completion_photo`) |
| GET | `/v1/leave-requests/mine` | Riwayat pengajuan izin sendiri |
| POST | `/v1/leave-requests` | Ajukan izin baru (`type`, `start_date`, `end_date`, `reason`) |

## 3. Endpoint baru — Company Admin (Super Admin)

| Method | Endpoint | Fungsi |
|---|---|---|
| PUT/DELETE/PATCH | `/v1/employees/{employee}`, `.../toggle-status` | Update, hapus, aktif/nonaktifkan karyawan |
| GET/POST/PUT/DELETE | `/v1/departments`, `/v1/positions`, `/v1/teams` | CRUD penuh (resource) |
| PATCH | `.../{id}/toggle-status` | Aktif/nonaktifkan department/position/team |
| GET | `/v1/offices`, `/v1/offices/{id}` | List & detail kantor |
| PUT | `/v1/offices/{office}` | Update kantor |
| GET | `/v1/attendance`, `/v1/attendance/{id}`, `/v1/attendance/statistics` | Monitoring absensi seluruh karyawan |
| GET | `/v1/leave-requests` | List semua pengajuan izin company |
| PATCH | `/v1/leave-requests/{leave}/approve` | Setujui izin |
| PATCH | `/v1/leave-requests/{leave}/reject` | Tolak izin (`rejection_reason` opsional) |
| PUT/DELETE | `/v1/assignments/{assignment}` | Update & hapus assignment |

## 4. Endpoint baru — Semua Role

| Method | Endpoint | Fungsi |
|---|---|---|
| GET | `/v1/profile` | Lihat profile (username, email, data employee/company) |
| PUT | `/v1/profile` | Update username/email, opsional ganti password (`current_password` + `password` + `password_confirmation`) |

## 5. Catatan keamanan

Endpoint yang mengubah data milik company (employee CUD, assignment
CUD, department/position/team/office CUD, approve/reject izin,
monitoring absensi semua karyawan) sekarang dilindungi middleware
`role:SUPER_ADMIN` — supaya user dengan role EMPLOYEE tidak bisa
memanggilnya langsung meski tahu URL-nya (mengikuti pembatasan yang
sama seperti versi web-nya). Endpoint `my-assignments/*` dan
`leave-requests` (submit) dilindungi `role:EMPLOYEE`.

Semua endpoint GET/POST yang SUDAH ADA sebelumnya (login, me, logout,
change-password, attendance milik sendiri, notifications, employees
index/show, master dropdown, assignments index/show/store, platform/*)
TIDAK diubah perilakunya — cuma ditambah, supaya tidak breaking change
untuk Flutter yang sudah kamu mulai kerjakan.

## 6. Yang masih di luar scope (kalau nanti dibutuhkan, tinggal bilang)

- Export absensi ke PDF/Excel lewat API (di web ada, tapi biasanya
  tidak dipakai dari mobile app — kalau perlu, gampang ditambahkan).
- Fitur subscription/premium self-service (checkout Midtrans) — saat
  ini hanya ada di web untuk Company Admin, belum ada versi API.
