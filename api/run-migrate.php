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

echo "Menjalankan migration...\n\n";

try {
    Artisan::call('migrate', ['--force' => true]);
    echo Artisan::output();
    echo "\n=== SELESAI ===\n";
} catch (\Throwable $e) {
    echo "GAGAL: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}