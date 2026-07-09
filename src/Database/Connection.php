<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use PDOException;

final class Connection
{
    private static ?PDO $instance = null;

    // The host's shared-hosting account occasionally hits a transient
    // per-account resource limit under load, which surfaces as PDO error
    // 2002 ("Operation not permitted") rather than a normal refused
    // connection. A couple of short-delay retries clears it in practice
    // without masking a real, persistent outage (still throws after this).
    private const MAX_ATTEMPTS = 3;
    private const RETRY_DELAY_MICROSECONDS = 150_000;

    public static function get(): PDO
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $config = require dirname(__DIR__, 2) . '/config/db.php';

        $dsn = "mysql:dbname={$config['database']};charset={$config['charset']}";
        $dsn .= $config['socket'] !== null
            ? ";unix_socket={$config['socket']}"
            : ";host={$config['host']};port={$config['port']}";

        $lastException = null;

        for ($attempt = 1; $attempt <= self::MAX_ATTEMPTS; $attempt++) {
            try {
                self::$instance = new PDO($dsn, $config['username'], $config['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);

                return self::$instance;
            } catch (PDOException $e) {
                $lastException = $e;
                if ($attempt < self::MAX_ATTEMPTS) {
                    usleep(self::RETRY_DELAY_MICROSECONDS);
                }
            }
        }

        throw new PDOException('Database connection failed: ' . $lastException->getMessage(), (int) $lastException->getCode());
    }
}
