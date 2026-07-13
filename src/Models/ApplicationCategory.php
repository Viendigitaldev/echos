<?php

declare(strict_types=1);

namespace App\Models;

final class ApplicationCategory extends Model
{
    protected static string $table = 'application_categories';

    /** @return array<string, mixed>|null */
    public static function findBySlug(string $slug): ?array
    {
        return static::findWhere(['slug' => $slug]);
    }

    /** @return array<int, array<string, mixed>> */
    public static function withPublishedCounts(): array
    {
        $sql = "SELECT c.id, c.name, c.slug, c.sort_order, COUNT(a.id) AS application_count
                FROM application_categories c
                LEFT JOIN applications a ON a.category_id = c.id AND a.status = 'published'
                GROUP BY c.id, c.name, c.slug, c.sort_order
                ORDER BY c.sort_order ASC, c.name ASC";

        return \App\Database\Connection::get()->query($sql)->fetchAll();
    }
}
