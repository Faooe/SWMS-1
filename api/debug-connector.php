<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain');
ini_set('output_buffering', 'off');
ob_implicit_flush(true);
while (ob_get_level() > 0) { ob_end_flush(); }

$config = config('database.connections.pgsql');

$dsn = "pgsql:host={$config['host']};dbname={$config['database']};port={$config['port']};sslmode=require;options=--endpoint={$config['endpoint']}";

echo "DSN: " . $dsn . "\n";
echo "Waktu mulai: " . date('H:i:s') . "\n";
flush();

$start = microtime(true);

try {
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_TIMEOUT => 8,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $elapsed = round(microtime(true) - $start, 2);
    echo "BERHASIL CONNECT dalam {$elapsed} detik!\n";
} catch (\PDOException $e) {
    $elapsed = round(microtime(true) - $start, 2);
    echo "GAGAL setelah {$elapsed} detik.\n";
    echo "Pesan error: " . $e->getMessage() . "\n";
}

echo "Waktu selesai: " . date('H:i:s') . "\n";