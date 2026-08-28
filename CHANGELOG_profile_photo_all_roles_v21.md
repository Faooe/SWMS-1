# Profile Photo All Roles v21

- Menambahkan `users.profile_photo` untuk foto akun semua role.
- Menambahkan API `POST /api/v1/profile/photo` dengan validasi JPEG/PNG/WebP maksimal 2 MB.
- Employee: foto profil disinkronkan ke `employees.photo` agar terlihat oleh Company.
- Company Admin: foto profil disinkronkan ke `companies.logo` agar terlihat oleh Platform.
- Platform Admin: foto profil tersimpan pada akun sendiri.
- Menambahkan upload foto pada halaman profile web untuk Platform Admin, Company Admin, dan Employee.
- Navbar web memakai foto profil terbaru bila tersedia.
