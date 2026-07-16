<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain');

$factory = $app->make(Illuminate\Database\Connectors\ConnectionFactory::class);
$connector = $factory->createConnector(['driver' => 'pgsql']);

echo "Class connector: " . get_class($connector) . "\n\n";

$config = config('database.connections.pgsql');

// Panggil getDsn() via reflection karena protected
$reflection = new ReflectionMethod($connector, 'getDsn');
$reflection->setAccessible(true);
$dsn = $reflection->invoke($connector, $config);

echo "DSN string yang beneran dipakai buat connect:\n";
echo $dsn . "\n\n";

echo "PHP version: " . phpversion() . "\n";
echo "pdo_pgsql loaded: " . (extension_loaded('pdo_pgsql') ? 'yes' : 'no') . "\n";

if (function_exists('pg_version')) {
    print_r(pg_version());
}

// Coba langsung PDO connect manual pakai DSN itu, tangkap errornya persis
echo "\n=== Percobaan koneksi langsung ===\n";
try {
    $pdo = new PDO($dsn, $config['username'], $config['password']);
    echo "BERHASIL CONNECT!\n";
} catch (\PDOException $e) {
    echo "GAGAL: " . $e->getMessage() . "\n";
}