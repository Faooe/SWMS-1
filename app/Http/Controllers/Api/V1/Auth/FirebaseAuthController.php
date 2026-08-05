<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Services\FirebaseAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * "Login dengan Google" versi API (dipakai Flutter mobile) -- padanan
 * App\Http\Controllers\Web\Auth\FirebaseLoginController tapi return
 * Sanctum token (bukan session + redirect_url), sama seperti bedanya
 * AuthController::login() (API) vs LoginController::authenticate() (web).
 *
 * Alur SAMA PERSIS dengan web: akun harus SUDAH ADA di sistem (dibuatkan
 * Aplikator/Company Admin), Google cuma cara alternatif membuktikan
 * identitas lewat Firebase ID Token yang didapat dari Flutter (paket
 * google_sign_in + firebase_auth) -- BUKAN pendaftaran akun baru.
 * Dicocokkan berdasarkan users.email, verifikasi token didelegasikan ke
 * FirebaseAuthService (SATU-SATUNYA tempat verifikasi ID token, dipakai
 * bareng-bareng sama controller web).
 */
class FirebaseAuthController extends Controller
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

            return ResponseHelper::error(
                'Login dengan Google belum dikonfigurasi di server. Hubungi Administrator.',
                null,
                500
            );

        }

        $email = $firebaseAuth->verifyIdTokenAndGetEmail(
            $request->string('id_token')->toString()
        );

        if (! $email) {

            return ResponseHelper::error(
                'Verifikasi Google gagal atau email belum terverifikasi. Coba lagi.',
                null,
                401
            );

        }

        try {

            $result = $this->authService->loginApiWithFirebase($email, $request);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return ResponseHelper::error(
                collect($e->errors())->flatten()->first() ?? 'Login dengan Google gagal.',
                $e->errors(),
                422
            );

        }

        return ResponseHelper::success(

            [
                'token' => $result['token'],
                'user' => new UserResource($result['user']),
            ],

            'Login berhasil.'

        );
    }
}
