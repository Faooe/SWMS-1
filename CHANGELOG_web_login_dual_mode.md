# Fix: Web login sekarang juga dukung NIP + Kode Company (bukan cuma email)

## Kenapa sebelumnya cuma bisa email
Ternyata bukan cuma soal API/Flutter yang belum lengkap -- **web login-nya
sendiri secara eksplisit menolak** input non-email:

```php
// LoginController::authenticate() -- SEBELUM fix
'login' => ['required', 'string', 'email'],   // <- rule 'email' MEMAKSA format
```

Dan `AuthService::loginWeb()` cuma cari `User::where('email', ...)`. Jadi
walau backend API sudah dibenerin sebelumnya, **web-nya sama sekali belum
ikut disentuh** -- padahal ada area `/employee/dashboard` yang sudah jadi
di web (attendance, dst), artinya employee memang dimaksudkan bisa akses
web juga, bukan cuma lewat app mobile.

## Yang diperbaiki

### 1. `AuthService.php`
Tambah `loginEmployeeWeb(array $credentials, Request $request): bool` --
mirror dari `loginByEmployeeNumber()` (versi API) tapi pakai
`Auth::login()` + session (sesuai pola `loginWeb()` yang sudah ada).

### 2. `LoginController.php`
`authenticate()` sekarang cabang berdasarkan field `login_mode`
(dikirim form, default `'email'` kalau tidak ada -- backward compatible):
- `login_mode = 'email'` → jalur lama, TIDAK diubah sama sekali.
- `login_mode = 'employee'` → validasi `company_code` + `employee_number`
  + `password`, panggil `loginEmployeeWeb()`.

### 3. `resources/views/auth/login.blade.php`
Tambah toggle "Email" / "NIP + Kode Company" di atas form.

**PENTING -- kenapa pakai vanilla JS, bukan Alpine `x-data`/`x-if`:**
Awalnya aku coba pakai Alpine (karena itu bagian dari stack kamu), tapi
ternyata halaman ini **standalone**, tidak `@extends('layouts.app')` dan
tidak include `@livewireScripts` -- sedangkan di project ini Alpine.js
CUMA di-boot lewat Livewire (lihat komentar di `resources/js/app.js`).
Jadi Alpine gak pernah jalan di halaman login ini. Kalau dipaksa pakai
`x-if`, field-nya malah gak pernah muncul sama sekali (bukan cuma
togglenya yang gak jalan). Makanya diganti pakai `<script>` vanilla JS
biasa (`swmsSwitchLoginMode()`) yang dijamin jalan di halaman manapun
tanpa bergantung Alpine/Livewire.

**Bonus temuan (belum difix, di luar scope kali ini)**: toggle mata
show/hide password (`x-ui.input` komponen, pakai Alpine juga) kemungkinan
sudah tidak berfungsi di halaman login ini dari sebelumnya, karena alasan
yang sama (Alpine gak jalan di sini). Password field-nya sendiri tetap
berfungsi normal, cuma tombol mata-nya kemungkinan diam saja kalau
diklik. Kabari kalau mau sekalian dibenerin (fix-nya kecil).

## File yang diubah
- `app/Services/AuthService.php`
- `app/Http/Controllers/Web/Auth/LoginController.php`
- `resources/views/auth/login.blade.php`

## Cara tes
1. `php artisan migrate` (kalau belum dari update sebelumnya)
2. Buka `/login` di browser.
3. Tab **Email** harus tetap seperti biasa -- coba login admin/company
   admin yang sudah ada, pastikan masih normal.
4. Klik tab **NIP + Kode Company** -- field berubah jadi Kode Company +
   NIP. Coba login pakai data employee yang sudah punya akun `User`
   ter-link (`employee_id`).
5. Coba submit form kosong di masing-masing tab -- pastikan pesan error
   validasi muncul dengan benar dan tab yang aktif tidak berubah sendiri
   setelah reload (`old('login_mode')` menjaga tab tetap di posisi yang
   benar kalau validasi gagal).
