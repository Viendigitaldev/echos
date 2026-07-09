<?php

declare(strict_types=1);

namespace App\Models;

use App\Database\Connection;

final class Setting extends Model
{
    protected static string $table = 'settings';
    protected static string $primaryKey = 'key';

    /** @var array<string, string>|null request-scoped cache — one query serves every get() */
    private static ?array $cache = null;

    public static function get(string $key, string $default = ''): string
    {
        self::loadCache();

        return self::$cache[$key] ?? $default;
    }

    public static function set(string $key, string $value): void
    {
        $stmt = Connection::get()->prepare(
            'INSERT INTO settings (`key`, `value`) VALUES (:key, :value)
             ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)'
        );
        $stmt->execute(['key' => $key, 'value' => $value]);

        self::$cache[$key] = $value;
    }

    /** @return array<int, array<string, mixed>> */
    public static function all(string $orderBy = ''): array
    {
        return Connection::get()->query('SELECT * FROM settings ORDER BY `key` ASC')->fetchAll();
    }

    private static function loadCache(): void
    {
        if (self::$cache !== null) {
            return;
        }

        self::$cache = [];
        foreach (Connection::get()->query('SELECT `key`, `value` FROM settings') as $row) {
            self::$cache[$row['key']] = (string) $row['value'];
        }
    }
}
