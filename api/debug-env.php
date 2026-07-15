<?php
header('Content-Type: text/plain');

$pass = getenv('DB_PASSWORD');
$user = getenv('DB_USERNAME');
$host = getenv('DB_HOST');

echo "DB_USERNAME: [" . $user . "] length: " . strlen($user) . "\n";
echo "DB_USERNAME hex: " . bin2hex($user) . "\n\n";

echo "DB_PASSWORD length: " . strlen($pass) . "\n";
echo "DB_PASSWORD hex: " . bin2hex($pass) . "\n\n";

echo "DB_HOST: [" . $host . "] length: " . strlen($host) . "\n";