<?php

/**
 * |--------------------------------------------------------------------------
 * | Vercel Entry Point untuk Laravel
 * |--------------------------------------------------------------------------
 * | File ini WAJIB ada di folder /api/index.php
 * | Tugasnya: siapin folder storage yang writable di /tmp,
 * | karena /var/task (folder project) itu read-only di Vercel.
 * |--------------------------------------------------------------------------
 */

// |--------------------------------------------------------------------------
// | 1. Arahkan Laravel storage path ke /tmp (satu-satunya folder writable)
// |--------------------------------------------------------------------------
$_ENV['LARAVEL_STORAGE_PATH']    = '/tmp/storage';
$_SERVER['LARAVEL_STORAGE_PATH'] = '/tmp/storage';
putenv('LARAVEL_STORAGE_PATH=/tmp/storage');

// |--------------------------------------------------------------------------
// | 2. Bikin semua folder storage yang dibutuhkan Laravel (kalau belum ada)
// |--------------------------------------------------------------------------
$storagePaths = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/testing',
    '/tmp/storage/logs',
    '/tmp/storage/app/public',
];

foreach ($storagePaths as $path) {
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
}

// |--------------------------------------------------------------------------
// | 3. Forward request ke entry point asli Laravel
// |--------------------------------------------------------------------------
require __DIR__ . '/../public/index.php';