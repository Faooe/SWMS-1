<?php

namespace App\Services;

use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Factory;
use RuntimeException;

/**
 * Verifikasi ID Token yang dikirim Firebase JS SDK dari browser (hasil
 * "Login dengan Google" -- lihat resources/views/auth/login.blade.php).
 *
 * INI CUMA VERIFIKASI, bukan cari/buat user. Pencocokan ke user lokal
 * (via email) dan pembuatan session tetap tanggung jawab
 * FirebaseLoginController + AuthService, sama seperti alur login
 * email/NIP yang sudah ada -- supaya semua jalur login (Email,
 * NIP+Kode Company, Google) berakhir di titik yang sama.
 */
class FirebaseAuthService
{
    private FirebaseAuth $auth;

    public function __construct()
    {
        $base64 = config('services.firebase.credentials_base64');

        if (blank($base64)) {

            throw new RuntimeException(

                'FIREBASE_CREDENTIALS_BASE64 belum di-set di .env -- ' .
                'Login dengan Google tidak bisa diverifikasi tanpa ini.'

            );

        }

        $json = base64_decode($base64, true);

        if ($json === false) {

            throw new RuntimeException(

                'FIREBASE_CREDENTIALS_BASE64 tidak valid (gagal decode base64).'

            );

        }

        $serviceAccount = json_decode($json, true);

        if (! is_array($serviceAccount)) {

            throw new RuntimeException(

                'FIREBASE_CREDENTIALS_BASE64 tidak valid (bukan JSON Service Account yang benar).'

            );

        }

        $this->auth = (new Factory())
            ->withServiceAccount($serviceAccount)
            ->createAuth();
    }

    /**
     * Verifikasi ID Token, return email (sudah dipastikan verified oleh
     * Google) kalau valid. Return null kalau token invalid/expired/
     * email belum diverifikasi Google.
     */
    public function verifyIdTokenAndGetEmail(string $idToken): ?string
    {
        try {

            $verifiedIdToken = $this->auth->verifyIdToken($idToken);

        } catch (FailedToVerifyToken) {

            return null;

        }

        $claims = $verifiedIdToken->claims();

        $emailVerified = $claims->get('email_verified', false);

        if (! $emailVerified) {

            return null;

        }

        $email = $claims->get('email');

        return is_string($email) ? $email : null;
    }
}
