<?php

use App\Http\Controllers\CronController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Cron Trigger Routes
|--------------------------------------------------------------------------
|
| Endpoint HTTP untuk memicu scheduled command, karena hosting serverless
| (Vercel) tidak menjalankan Laravel Scheduler secara otomatis. Semua
| endpoint di sini dilindungi CRON_SECRET (lihat CronController::authorize).
|
| Sengaja TIDAK diberi middleware 'auth' apapun karena pemanggilnya adalah
| Vercel Cron / cron eksternal, bukan user yang login -- keamanannya
| murni dari header Authorization: Bearer <CRON_SECRET>.
|
*/

Route::prefix('cron')->group(function () {

    Route::get('/mark-absent', [CronController::class, 'markAbsent']);

    Route::get('/activate-assignments', [CronController::class, 'activateAssignments']);

    Route::get('/subscriptions-downgrade', [CronController::class, 'downgradeExpiredSubscriptions']);

});