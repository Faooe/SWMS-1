# Backend fix: username/email scoping + login pakai NIP (opsional, email tetap jalan)

## Ringkasan temuan (dari review perubahan kamu)

Migration yang sudah kamu/tim buat sebelumnya **SUDAH BENAR**:
- `2026_07_21_063000_scope_employee_number_email_unique_per_company.php`
- `2026_07_25_090000_scope_username_unique_per_company_on_users_table.php`

Keduanya sudah tepat mengubah `employees.employee_number`, `employees.email`,
dan `users.username` dari unique GLOBAL jadi composite unique per company.
**`users.email` sengaja tidak disentuh** -- tetap global, karena itu satu-
satunya identifier login sampai update ini (lihat komentar di `LoginRequest`:
"Login sekarang HANYA menggunakan Email... supaya tidak ambigu di sistem
multi-company" -- keputusan ini sudah tepat dari awal).

**Tapi ada 2 tempat yang belum ikut disesuaikan ke migration itu**, jadi
validasinya bisa bilang "aman" padahal database akan menolak (atau
sebaliknya, generator auto-username tidak seefektif yang seharusnya):

### Bug 1: `EmployeeImportService::generateUsername()` masih cek GLOBAL
Komentar di kode ini sendiri bilang "unik global, sesuai constraint
users.username" -- tapi itu sudah basi, constraint-nya sudah diubah jadi
per-company oleh migration di atas. **Fixed**: sekarang di-scope by
`company_id`, sama seperti `generateEmployeeNumber()` di file yang sama
(yang sudah benar dari awal, jadi tinggal disamakan polanya).

### Bug 2: `CompanyRequest::admin_username` kehilangan validasi total saat CREATE
```php
// SEBELUM (bug):
'admin_username' => array_filter([
    ...
    $companyId ? Rule::unique(...) : null,   // <- null saat create = rule HILANG
]),
```
Karena `array_filter()` membuang elemen `null`, saat bikin company BARU
(`$companyId` masih `null`), rule unique-nya ikut hilang -- bukan cuma
"di-skip dengan aman", tapi validasi format (`string`, `min:4`, `max:50`)
JUGA ikut ketimpa hilang tanpa disadari kalau elemen array_filter-nya salah
posisi. **Fixed**: rule format selalu jalan, dan rule unique sekarang selalu
ada (di-scope ke `company_id` yang sesuai -- untuk create, otomatis
ke-scope ke company_id NULL yang aman, tidak pernah salah blokir company lain).

## Fitur baru: Login pakai NIP + Kode Company (opsional)

Sesuai request kamu ("tambahkan juga bisa pakai gmail") -- **login lewat
email TETAP ada dan tidak diubah sama sekali**. ini cuma nambah alternatif
untuk employee yang belum/tidak punya email, supaya company tidak wajib
bikinkan Gmail cuma demi bisa login.

**Endpoint baru**: `POST /api/v1/login/employee`
```json
{
  "company_code": "ABC",
  "employee_number": "001",
  "password": "..."
}
```
Response-nya PERSIS sama bentuknya dengan `POST /api/v1/login` yang lama
(`{ "data": { "token": ..., "user": {...} } }`), jadi sisi Flutter nanti
tinggal panggil endpoint yang beda, proses hasilnya sama persis.

**File baru**:
- `app/Http/Requests/Auth/EmployeeLoginRequest.php`

**File yang diubah**:
- `app/Services/AuthService.php` -- tambah `loginByEmployeeNumber()`
- `app/Http/Controllers/Api/V1/Auth/AuthController.php` -- tambah `loginEmployee()`
- `routes/api.php` -- tambah route `/login/employee`
- `app/Services/Employee/EmployeeImportService.php` -- fix Bug 1 di atas
- `app/Http/Requests/CompanyRequest.php` -- fix Bug 2 di atas

## WAJIB: jalankan migration kalau belum

```bash
php artisan migrate
```
Cek dulu statusnya kalau ragu apakah 2 migration itu sudah jalan di database
kamu (local/production):
```bash
php artisan migrate:status
```

## Yang BELUM dikerjakan (langkah selanjutnya)

Flutter belum bisa pakai endpoint `/login/employee` ini -- layar login di
mobile masih cuma punya 1 form (email + password). Perlu ditambah semacam
toggle "Login sebagai Admin" (email) vs "Login sebagai Karyawan" (Kode
Company + NIP). Kabari kalau mau aku lanjutkan ke situ.

## Cara tes
1. `php artisan migrate` (kalau belum)
2. Coba bikin 2 company beda dengan `admin_username` yang SAMA persis --
   sekarang harusnya keduanya berhasil (sebelumnya salah satu gagal).
3. Coba bikin 2 employee di company berbeda dengan `employee_number`
   ("NIP") yang sama, mis. sama-sama "001" -- sekarang harusnya berhasil.
4. Tes endpoint baru lewat curl:
   ```
   curl -X POST https://<domain>/api/v1/login/employee \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{"company_code":"ABC","employee_number":"001","password":"..."}'
   ```
5. Pastikan login lewat email (`/login`) masih jalan normal seperti biasa
   untuk akun yang sudah ada.
