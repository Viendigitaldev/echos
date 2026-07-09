<?php

declare(strict_types=1);

namespace App\Models;

use App\Database\Connection;

final class BlogPost extends Model
{
    protected static string $table = 'blog_posts';

    /**
     * All published posts, category-joined. The listing page filters/sorts
     * this set entirely client-side (search, multi-select category, sort —
     * matching the Applications page's tab-filter UX), so there's no
     * server-side pagination/filtering to do here.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function publishedAll(): array
    {
        $stmt = Connection::get()->query(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug
             FROM blog_posts p
             LEFT JOIN blog_categories c ON c.id = p.category_id
             WHERE p.status = 'published'
             ORDER BY p.published_at DESC"
        );

        return $stmt->fetchAll();
    }

    /** @return array<string, mixed>|null */
    public static function findPublishedBySlug(string $slug): ?array
    {
        $stmt = Connection::get()->prepare(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug
             FROM blog_posts p
             LEFT JOIN blog_categories c ON c.id = p.category_id
             WHERE p.slug = :slug AND p.status = 'published'
             LIMIT 1"
        );
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    /** @return array<int, array<string, mixed>> */
    public static function latestPublished(int $limit = 2): array
    {
        $stmt = Connection::get()->prepare(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug
             FROM blog_posts p
             LEFT JOIN blog_categories c ON c.id = p.category_id
             WHERE p.status = 'published'
             ORDER BY p.published_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
