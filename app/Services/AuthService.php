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

        $user = User::with([
            'role',
            'employee.currentEmployment.department',
            'employee.currentEmployment.position',
            'employee.currentEmployment.team',
            'employee.currentEmployment.office',
            'employee.currentEmployment.shift',
        ])
            ->where(function ($query) use ($credentials) {

                $query->where('username', $credentials['login'])
                    ->orWhere('email', $credentials['login']);

            })
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {

            throw ValidationException::withMessages([
                'login' => [
                    'Username/email atau password salah.'
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

        // 1. Cari user secara manual berdasarkan email atau username
        $user = User::where(function ($query) use ($credentials) {
            $query->where('username', $credentials['login'])
                  ->orWhere('email', $credentials['login']);
        })->first();

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