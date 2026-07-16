<?php
header('Content-Type: text/plain');

echo "=== Semua ENV var yang mengandung 'DB' atau 'ENDPOINT' ===\n\n";

foreach ($_ENV as $key => $value) {
    if (stripos($key, 'DB') !== false || stripos($key, 'ENDPOINT') !== false) {
        echo "[" . $key . "] (key length: " . strlen($key) . ", key hex: " . bin2hex($key) . ")\n";
        echo "  value: " . $value . "\n";
        echo "  value length: " . strlen($value) . "\n\n";
    }
}

echo "=== Cek langsung getenv('DB_ENDPOINT') ===\n";
$direct = getenv('DB_ENDPOINT');
var_dump($direct);