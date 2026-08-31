<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Automation Schedules
|--------------------------------------------------------------------------
*/

// Phase 2: reminder H-7/H-3/H-1 lalu downgrade subscription yang sudah lewat.
Schedule::command('subscriptions:send-expiry-reminders')
    ->dailyAt('00:01');

Schedule::command('subscriptions:downgrade-expired')
    ->dailyAt('00:05');

// Menjalankan pengecekan Absent (Alpa/Mangkir) tiap 15 menit -- supaya
// karyawan ditandai Absent tidak lama setelah jam pulang (shift end)
// masing-masing terlewati, bukan menumpuk baru ketahuan tengah malam.
Schedule::command('attendance:mark-absent')
    ->everyFifteenMinutes();

// Mengubah Assignment Draft menjadi Assigned otomatis saat jadwalnya tiba
Schedule::command('assignments:activate-scheduled')
    ->everyFiveMinutes();

// Auto-expire revisi assignment yang kelewat batas waktu (+ toleransi
// 30 menit) tanpa di-resubmit employee -- lihat App\Console\Commands\
// ExpireAssignmentRevisions & AssignmentEmployee.review_status.
Schedule::command('assignments:expire-revisions')
    ->everyFiveMinutes();