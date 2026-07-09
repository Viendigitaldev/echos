<?php

declare(strict_types=1);

namespace App\Models;

final class BlogCategory extends Model
{
    protected static string $table = 'blog_categories';

    /** @return array<string, mixed>|null */
    public static function findBySlug(string $slug): ?array
    {
        return static::findWhere(['slug' => $slug]);
    }

    /** @return array<int, array<string, mixed>> */
    public static function withPublishedCounts(): array
    {
        $sql = "SELECT c.id, c.name, c.slug, COUNT(p.id) AS post_count
                FROM blog_categories c
                LEFT JOIN blog_posts p ON p.category_id = c.id AND p.status = 'published'
                GROUP BY c.id, c.name, c.slug
                ORDER BY c.name ASC";

        return \App\Database\Connection::get()->query($sql)->fetchAll();
    }
}
