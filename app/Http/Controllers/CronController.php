<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CronController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Controller untuk Trigger Scheduled Command via HTTP
    |--------------------------------------------------------------------------
    |
    | Karena project ini di-deploy di Vercel (serverless), Laravel Scheduler
    | (Schedule::command di routes/console.php) tidak bisa jalan sendiri --
    | butuh sesuatu dari luar yang "mengetuk" endpoint ini secara berkala
    | (Vercel Cron Jobs untuk yang harian, atau cron eksternal seperti
    | cron-job.org untuk yang tiap 5 menit).
    |
    | Semua endpoint di sini WAJIB dilindungi CRON_SECRET -- lihat method
    | isValidCronRequest() di bawah.
    |
    | Catatan: method ini SENGAJA tidak dinamai authorize() -- nama itu
    | sudah dipakai Laravel sendiri (trait AuthorizesRequests di parent
    | Controller punya public function authorize()), jadi kalau dipakai
    | ulang dengan visibility private akan bikin Fatal Error.
    |
    */

    /**
     * Validasi request datang dari pemanggil yang sah (Vercel Cron / cron
     * eksternal yang sudah dikasih tahu CRON_SECRET-nya), bukan orang asing
     * yang kebetulan tahu URL-nya.
     *
     * Vercel Cron otomatis mengirim header:
     *   Authorization: Bearer <CRON_SECRET>
     * asal env CRON_SECRET sudah di-set di Project Settings > Environment
     * Variables. Untuk cron eksternal (cron-job.org dkk), header yang sama
     * harus diset manual di pengaturan job-nya.
     */
    private function isValidCronRequest(Request $request): bool
    {
        $secret = config('services.cron.secret');

        if (empty($secret)) {
            // CRON_SECRET belum di-set sama sekali di env -> tolak semua,
            // supaya endpoint ini tidak pernah "terbuka" tanpa sengaja.
            return false;
        }

        $header = (string) $request->bearerToken();

        return hash_equals($secret, $header);
    }

    private function unauthorized()
    {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized.',
        ], 401);
    }

    /*
    |--------------------------------------------------------------------------
    | GET /cron/mark-absent
    |--------------------------------------------------------------------------
    | Ganti Schedule::command('attendance:mark-absent')->dailyAt('23:59')
    | Cocok dipicu via Vercel Cron (gratis, 1x sehari, plan Hobby).
    */
    public function markAbsent(Request $request)
    {
        if (!$this->isValidCronRequest($request)) {
            return $this->unauthorized();
        }

        Artisan::call('attendance:mark-absent');

        return response()->json([
            'success' => true,
            'output' => trim(Artisan::output()),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | GET /cron/activate-assignments
    |--------------------------------------------------------------------------
    | Ganti Schedule::command('assignments:activate-scheduled')->everyFiveMinutes()
    | Karena butuh tiap 5 menit, Vercel Cron plan Hobby TIDAK BISA -- pakai
    | cron eksternal gratis (cron-job.org, Crontap, dll) yang memanggil
    | endpoint ini tiap 5 menit dengan header Authorization: Bearer <secret>.
    */
    public function activateAssignments(Request $request)
    {
        if (!$this->isValidCronRequest($request)) {
            return $this->unauthorized();
        }

        Artisan::call('assignments:activate-scheduled');

        return response()->json([
            'success' => true,
            'output' => trim(Artisan::output()),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | GET /cron/subscriptions-downgrade
    |--------------------------------------------------------------------------
    | Ganti Schedule::command('subscriptions:downgrade-expired')->dailyAt('00:05')
    | Sudah ada di routes/console.php sebelumnya, ikut ditambahkan di sini
    | supaya SEMUA scheduled command di project ini punya jalur trigger via
    | HTTP -- bukan cuma yang ditanyakan soal absen & assignment.
    */
    public function downgradeExpiredSubscriptions(Request $request)
    {
        if (!$this->isValidCronRequest($request)) {
            return $this->unauthorized();
        }

        Artisan::call('subscriptions:downgrade-expired');

        return response()->json([
            'success' => true,
            'output' => trim(Artisan::output()),
        ]);
    }
}