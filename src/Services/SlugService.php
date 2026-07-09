<?php

declare(strict_types=1);

namespace App\Services;

use App\Database\Connection;

final class SlugService
{
    public static function slugify(string $text): string
    {
        $slug = strtolower(trim($text));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? '';
        return trim($slug, '-');
    }

    /**
     * Appends -2, -3, ... until the slug is unique in $table.$column,
     * optionally excluding a row (used when editing an existing record).
     */
    public static function unique(string $table, string $column, string $text, ?int $excludeId = null): string
    {
        $base = self::slugify($text);
        $slug = $base;
        $suffix = 2;

        while (self::exists($table, $column, $slug, $excludeId)) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    private static function exists(string $table, string $column, string $slug, ?int $excludeId): bool
    {
        $sql = "SELECT COUNT(*) FROM {$table} WHERE {$column} = :slug";
        $params = ['slug' => $slug];

        if ($excludeId !== null) {
            $sql .= ' AND id != :id';
            $params['id'] = $excludeId;
        }

        $stmt = Connection::get()->prepare($sql);
        $stmt->execute($params);

        return ((int) $stmt->fetchColumn()) > 0;
    }
}
