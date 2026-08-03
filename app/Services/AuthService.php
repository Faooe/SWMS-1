<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
{
    /**
     * Login API (Sanctum Token)
     */
    public function loginApi(
        array $credentials,
        Request $request
    ): array {

        $loginInput = $credentials['login'];

        $user = User::with([
            'role',
            'company',
            'employee.currentEmployment.department',
            'employee.currentEmployment.position',
            'employee.currentEmployment.team',
            'employee.currentEmployment.office',
            'employee.currentEmployment.shift',
        ])
            ->where('email', $loginInput)
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {

            throw ValidationException::withMessages([
                'login' => [
                    'Email atau password salah.'
                ]
            ]);

        }

        if (! $user->is_active) {

            throw ValidationException::withMessages([
                'login' => [
                    'Akun tidak aktif.'
                ]
            ]);

        }

        if ($user->company_id && (!$user->company || !$user->company->is_active)) {

            throw ValidationException::withMessages([
                'login' => [
                    'Perusahaan Anda telah dinonaktifkan oleh Administrator.'
                ]
            ]);

        }

        // Hapus token lama
        $user->tokens()->delete();

        // Token baru
        $token = $user->createToken('mobile')->plainTextToken;

        // Update login
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        return [

            'token' => $token,

            'user' => $user,

        ];
    }

    /**
     * Login API khusus Employee, pakai Kode Company + NIP (bukan email).
     *
     * Response shape SENGAJA dibuat identik dengan loginApi() (sama-sama
     * ['token' => ..., 'user' => ...]) supaya AuthController & sisi
     * Flutter tidak perlu logic parsing terpisah untuk endpoint ini --
     * cukup panggil endpoint yang beda, hasilnya diproses dengan cara
     * yang sama persis seperti login lewat email.
     */
    public function loginByEmployeeNumber(
        string $companyCode,
        string $employeeNumber,
        string $password,
        Request $request
    ): array {

        $company = \App\Models\Company::where('code', strtoupper(trim($companyCode)))->first();

        if (! $company) {

            throw ValidationException::withMessages([
                'company_code' => [
                    'Kode Company tidak ditemukan.'
                ]
            ]);

        }

        $employee = \App\Models\Employee::where('company_id', $company->id)
            ->where('employee_number', trim($employeeNumber))
            ->first();

        if (! $employee) {

            throw ValidationException::withMessages([
                'employee_number' => [
                    'NIP tidak ditemukan di company ini.'
                ]
            ]);

        }

        $user = User::with([
            'role',
            'company',
            'employee.currentEmployment.department',
            'employee.currentEmployment.position',
            'employee.currentEmployment.team',
            'employee.currentEmployment.office',
            'employee.currentEmployment.shift',
        ])
            ->where('employee_id', $employee->id)
            ->first();

        if (! $user || ! Hash::check($password, $user->password)) {

            throw ValidationException::withMessages([
                'password' => [
                    'NIP atau password salah.'
                ]
            ]);

        }

        if (! $user->is_active) {

            throw ValidationException::withMessages([
                'password' => [
                    'Akun tidak aktif.'
                ]
            ]);

        }

        if (! $company->is_active) {

            throw ValidationException::withMessages([
                'password' => [
                    'Perusahaan Anda telah dinonaktifkan oleh Administrator.'
                ]
            ]);

        }

        // Hapus token lama
        $user->tokens()->delete();

        // Token baru
        $token = $user->createToken('mobile')->plainTextToken;

        // Update login
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        return [

            'token' => $token,

            'user' => $user,

        ];
    }

    /**
     * Login Web (Laravel Session)
     */
    public function loginWeb(
        array $credentials,
        Request $request
    ): bool {

        // 1. Cari user secara manual berdasarkan email
        $loginInput = $credentials['login'];

        $user = User::where('email', $loginInput)->first();

        // 2. Validasi keberadaan user, kecocokan password, dan status aktif
        if (! $user || ! Hash::check($credentials['password'], $user->password) || ! $user->is_active) {
            return false;
        }

        // 3. Jika valid, login langsung menggunakan instance objek user
        Auth::login($user);

        $request->session()->regenerate();

        $user->update([

            'last_login_at' => now(),

            'last_login_ip' => $request->ip(),

        ]);

        return true;
    }

    /**
     * Login Web khusus Employee, pakai Kode Company + NIP (bukan email) --
     * mirror dari loginByEmployeeNumber() (versi API) tapi pakai session
     * Auth::login() seperti loginWeb(), untuk employee yang login lewat
     * browser (area /employee/* di web), bukan lewat app mobile.
     */
    /**
     * Bagian akhir dari proses login (Auth::login + regenerate session +
     * catat last_login) -- diekstrak dari loginWeb() supaya bisa dipakai
     * ulang oleh FirebaseLoginController setelah email hasil verifikasi
     * Google ketemu user-nya. User yang dioper ke sini WAJIB sudah
     * lolos pengecekan is_active (dicek di pemanggil, sama seperti
     * pengecekan company aktif yang juga tetap di controller/loginWeb).
     */
    public function establishWebSession(User $user, Request $request): void
    {
        Auth::login($user);

        $request->session()->regenerate();

        $user->update([

            'last_login_at' => now(),

            'last_login_ip' => $request->ip(),

        ]);
    }

    public function loginEmployeeWeb(
        array $credentials,
        Request $request
    ): bool {

        $company = \App\Models\Company::where(
            'code',
            strtoupper(trim($credentials['company_code']))
        )->first();

        if (! $company) {
            return false;
        }

        $employee = \App\Models\Employee::where('company_id', $company->id)
            ->where('employee_number', trim($credentials['employee_number']))
            ->first();

        if (! $employee) {
            return false;
        }

        $user = User::where('employee_id', $employee->id)->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password) || ! $user->is_active) {
            return false;
        }

        Auth::login($user);

        $request->session()->regenerate();

        $user->update([

            'last_login_at' => now(),

            'last_login_ip' => $request->ip(),

        ]);

        return true;
    }

    /**
     * Logout API
     */
    public function logout(User $user): void
    {
        /** @var PersonalAccessToken|null $token */
        $token = $user->currentAccessToken();

        if ($token) {

            $token->delete();

        }
    }

    /**
     * Logout Web
     */
    public function logoutWeb(
        Request $request
    ): void {

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

    }

    /**
     * Change Password
     */
    public function changePassword(
        User $user,
        array $data
    ): void {

        if (! Hash::check(
            $data['current_password'],
            $user->password
        )) {

            throw ValidationException::withMessages([
                'current_password' => [
                    'Password lama tidak sesuai.'
                ]
            ]);

        }

        $user->update([

            'password' => Hash::make($data['new_password']), // Diperbaiki menggunakan Hash::make

            'password_changed_at' => now(),

        ]);

    }
}