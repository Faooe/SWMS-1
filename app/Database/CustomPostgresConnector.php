<?php

namespace App\Database;

use Illuminate\Database\Connectors\PostgresConnector as BasePostgresConnector;

class CustomPostgresConnector extends BasePostgresConnector
{
    /**
     * Override supaya parameter "endpoint" (dibutuhkan Neon untuk routing
     * di client yang belum support SNI) beneran disisipkan ke connection
     * string (DSN), bukan diperlakukan sebagai PDO driver attribute.
     */
    protected function getDsn(array $config)
    {
        $dsn = parent::getDsn($config);

        if (! empty($config['endpoint'])) {
            $dsn .= ";options=--endpoint=" . $config['endpoint'];
        }

        return $dsn;
    }
}