<?php
// Bootstrap Laravel biar bisa akses container-nya
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain');

$factory = $app->make(Illuminate\Database\Connectors\ConnectionFactory::class);
$connector = $factory->createConnector(['driver' => 'pgsql']);

echo "Class connector yang beneran dipakai:\n";
echo get_class($connector) . "\n\n";

echo "Apakah container 'db.connector.pgsql' bound?\n";
var_dump($app->bound('db.connector.pgsql'));

echo "\nIsi config database.connections.pgsql.endpoint:\n";
var_dump(config('database.connections.pgsql.endpoint'));

echo "\nApakah class App\\Database\\CustomPostgresConnector ada (class_exists)?\n";
var_dump(class_exists(\App\Database\CustomPostgresConnector::class));