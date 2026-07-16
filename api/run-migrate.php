<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain');

// Ganti jadi kata sandi rahasia biar nggak sembarang orang bisa trigger ini
$secret = $_GET['secret'] ?? '';
if ($secret !== 'ganti-ini-jadi-rahasia-123') {
    http_response_code(403);
    echo "Forbidden. Tambahkan ?secret=... yang benar di URL.";
    exit;
}

use Illuminate\Support\Facades\Artisan;

echo "Menjalankan migrate:fresh (Menghapus semua tabel dan membuat ulang dari nol)...\n\n";

try {
    // Menggunakan migrate:fresh untuk memastikan database benar-benar bersih dan urut
    Artisan::call('migrate:fresh', ['--force' => true]);
    echo Artisan::output();
    echo "\n=== MIGRASI SELESAI ===\n";
    
    echo "Menjalankan seeder...\n";
    Artisan::call('db:seed', ['--force' => true]);
    echo Artisan::output();
    echo "\n=== SEEDER SELESAI ===\n";
    
} catch (\Throwable $e) {
    echo "GAGAL: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}