<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use App\Services\FirebaseAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * "Login dengan Google" (Firebase Auth SSO) -- alur login KETIGA di
 * samping Email dan NIP+Kode Company (lihat LoginController), BUKAN
 * pengganti keduanya. Prinsipnya sama seperti loginWeb(): akun harus
 * SUDAH ADA di sistem (dibuatkan Aplikator/Company Admin), Google cuma
 * jadi cara alternatif membuktikan identitas -- BUKAN pendaftaran akun
 * baru. Dicocokkan berdasarkan users.email.
 *
 * Sengaja return JSON (bukan redirect biasa) karena dipanggil lewat
 * fetch() dari JS di resources/views/auth/login.blade.php setelah
 * popup Google berhasil -- JS yang urus redirect browser-nya lewat
 * `redirect_url` yang dikembalikan di sini.
 */
class FirebaseLoginController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'id_token' => ['required', 'string'],
        ]);

        try {

            $firebaseAuth = app(FirebaseAuthService::class);

        } catch (\RuntimeException $e) {

            report($e);

            return response()->json([
                'message' => 'Login dengan Google belum dikonfigurasi di server. Hubungi Administrator.',
            ], 500);

        }

        $email = $firebaseAuth->verifyIdTokenAndGetEmail(
            $request->string('id_token')->toString()
        );

        if (! $email) {

            return response()->json([
                'message' => 'Verifikasi Google gagal atau email belum terverifikasi. Coba lagi.',
            ], 401);

        }

        /** @var User|null $user */
        $user = User::where('email', $email)->first();

        if (! $user) {

            return response()->json([
                'message' => "Akun dengan email {$email} tidak ditemukan di sistem. " .
                    'Hubungi Aplikator atau Admin perusahaan Anda untuk dibuatkan akun terlebih dahulu.',
            ], 404);

        }

        if (! $user->isActive()) {

            return response()->json([
                'message' => 'Akun Anda sudah tidak aktif.',
            ], 403);

        }

        if ($user->company_id && (! $user->company || ! $user->company->is_active)) {

            return response()->json([
                'message' => 'Perusahaan Anda telah dinonaktifkan oleh Administrator.',
            ], 403);

        }

        $this->authService->establishWebSession($user, $request);

        /*
        |--------------------------------------------------------------------------
        | Redirect By Role -- SAMA PERSIS dengan LoginController::authenticate(),
        | sengaja diduplikasi (bukan di-refactor jadi shared method) supaya
        | LoginController yang sudah teruji tidak ikut disentuh/berisiko
        | berubah perilakunya gara-gara perubahan di sini.
        |--------------------------------------------------------------------------
        */

        if ($user->isPlatformAdmin()) {

            return response()->json([
                'redirect_url' => route('platform.dashboard'),
            ]);

        }

        if ($user->isSuperAdmin()) {

            return response()->json([
                'redirect_url' => route('dashboard'),
            ]);

        }

        if ($user->isEmployee()) {

            return response()->json([
                'redirect_url' => route('employee.dashboard'),
            ]);

        }

        Auth::logout();

        return response()->json([
            'message' => 'Role akun tidak dikenali.',
        ], 403);
    }
}
