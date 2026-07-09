<?php

declare(strict_types=1);

namespace App\Models;

use App\Database\Connection;

final class ApplicationItem extends Model
{
    protected static string $table = 'applications';

    /** @return array<int, array<string, mixed>> */
    public static function forHomeSlider(): array
    {
        $stmt = Connection::get()->prepare(
            "SELECT a.*, c.name AS category_name, c.slug AS category_slug
             FROM applications a
             LEFT JOIN application_categories c ON c.id = a.category_id
             WHERE a.status = 'published' AND a.show_on_home = 1
             ORDER BY a.sort_order ASC"
        );
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /** @return array<int, array<string, mixed>> */
    public static function forApplicationsPage(): array
    {
        $stmt = Connection::get()->prepare(
            "SELECT a.*, c.name AS category_name, c.slug AS category_slug
             FROM applications a
             LEFT JOIN application_categories c ON c.id = a.category_id
             WHERE a.status = 'published' AND a.show_on_applications_page = 1
             ORDER BY a.sort_order ASC"
        );
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /** @return array<int, array<string, mixed>> */
    public static function allWithCategoryNames(): array
    {
        $stmt = Connection::get()->prepare(
            "SELECT a.*, c.name AS category_name
             FROM applications a
             LEFT JOIN application_categories c ON c.id = a.category_id
             ORDER BY a.sort_order ASC"
        );
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /** @return array<string, mixed>|null */
    public static function findBySlug(string $slug): ?array
    {
        return static::findWhere(['slug' => $slug]);
    }
}
