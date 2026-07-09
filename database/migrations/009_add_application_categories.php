<?php

/**
 * Replaces applications.category (free-text) with a real taxonomy, mirroring
 * blog_categories/blog_posts.category_id exactly. The old tabs on
 * /applications were hardcoded HTML disconnected from the DB — this makes
 * them admin-managed and reconnects them to real data.
 *
 * Idempotent — safe to run more than once. Run once: php database/migrations/009_add_application_categories.php
 */

declare(strict_types=1);

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use App\Database\Connection;
use App\Services\SlugService;

$pdo = Connection::get();

$pdo->exec(
    'CREATE TABLE IF NOT EXISTS application_categories (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        slug VARCHAR(100) NOT NULL,
        UNIQUE KEY uq_application_categories_slug (slug)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
);
echo "application_categories table ready.\n";

$hasCategoryColumn = $pdo->query(
    "SELECT COUNT(*) FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'applications' AND COLUMN_NAME = 'category'"
)->fetchColumn() > 0;

if ($hasCategoryColumn) {
    $names = $pdo->query(
        "SELECT DISTINCT category FROM applications WHERE category IS NOT NULL AND category != ''"
    )->fetchAll(PDO::FETCH_COLUMN);

    foreach ($names as $name) {
        $exists = $pdo->prepare('SELECT id FROM application_categories WHERE name = :name');
        $exists->execute(['name' => $name]);
        if ($exists->fetchColumn() !== false) {
            continue;
        }

        $stmt = $pdo->prepare('INSERT INTO application_categories (name, slug) VALUES (:name, :slug)');
        $stmt->execute(['name' => $name, 'slug' => SlugService::unique('application_categories', 'slug', $name)]);
        echo "Seeded category '{$name}'.\n";
    }

    $hasCategoryId = $pdo->query(
        "SELECT COUNT(*) FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'applications' AND COLUMN_NAME = 'category_id'"
    )->fetchColumn() > 0;

    if (!$hasCategoryId) {
        $pdo->exec('ALTER TABLE applications ADD COLUMN category_id INT UNSIGNED NULL AFTER category');
        $pdo->exec('ALTER TABLE applications ADD INDEX idx_applications_category (category_id)');
        $pdo->exec(
            'ALTER TABLE applications ADD CONSTRAINT fk_applications_category
             FOREIGN KEY (category_id) REFERENCES application_categories(id) ON DELETE SET NULL'
        );
        echo "applications.category_id added.\n";
    }

    $pdo->exec(
        'UPDATE applications a
         JOIN application_categories c ON c.name = a.category
         SET a.category_id = c.id'
    );
    echo "Migrated existing category text to category_id.\n";

    $pdo->exec('ALTER TABLE applications DROP COLUMN category');
    echo "Dropped legacy applications.category column.\n";
} else {
    echo "applications.category already dropped — nothing to migrate.\n";
}
