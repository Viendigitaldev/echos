<?php

/**
 * Adds the `media` table (the catalog backing the admin Media Library +
 * mandatory alt text) and backfills a row for every image already
 * referenced by existing content, so the library is complete from day one.
 *
 * Alt text is deliberately shared per uploaded file (one row in `media`,
 * looked up by path) rather than duplicated as a column on every content
 * table — confirmed with the manager. Backfilled alt text is auto-filled
 * from the owning record's title/name; nothing here touches the content
 * tables themselves (no new columns, no FKs).
 *
 * Idempotent — safe to run more than once. Run once: php database/migrations/008_add_media_library.php
 */

declare(strict_types=1);

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use App\Database\Connection;

$pdo = Connection::get();

$pdo->exec(
    'CREATE TABLE IF NOT EXISTS media (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        path VARCHAR(255) NOT NULL,
        original_filename VARCHAR(255) NULL,
        mime_type VARCHAR(100) NULL,
        size_bytes INT UNSIGNED NULL,
        width SMALLINT UNSIGNED NULL,
        height SMALLINT UNSIGNED NULL,
        alt_text VARCHAR(255) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uq_media_path (path)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
);
echo "media table ready.\n";

function backfillPath(PDO $pdo, ?string $path, string $altText): void
{
    $path = trim((string) $path);
    if ($path === '' || preg_match('#^https?://#i', $path) === 1) {
        return; // nothing to catalog: empty, or an external hotlinked URL
    }

    $exists = $pdo->prepare('SELECT id FROM media WHERE path = :path');
    $exists->execute(['path' => $path]);
    if ($exists->fetchColumn() !== false) {
        return;
    }

    $absolute = dirname(__DIR__, 2) . '/storage/' . ltrim($path, '/');
    $width = null;
    $height = null;
    $mime = null;
    $size = null;

    if (is_file($absolute)) {
        $size = filesize($absolute) ?: null;
        $info = @getimagesize($absolute);
        if ($info !== false) {
            $width = $info[0];
            $height = $info[1];
            $mime = $info['mime'] ?? null;
        }
    }

    $stmt = $pdo->prepare(
        'INSERT INTO media (path, original_filename, mime_type, size_bytes, width, height, alt_text)
         VALUES (:path, :original_filename, :mime_type, :size_bytes, :width, :height, :alt_text)'
    );
    $stmt->execute([
        'path' => $path,
        'original_filename' => basename($path),
        'mime_type' => $mime,
        'size_bytes' => $size,
        'width' => $width,
        'height' => $height,
        'alt_text' => $altText !== '' ? $altText : 'Uploaded image',
    ]);
    echo "Backfilled media row for '{$path}'.\n";
}

foreach ($pdo->query('SELECT image_path, title FROM applications') as $row) {
    backfillPath($pdo, $row['image_path'], (string) $row['title']);
}

foreach ($pdo->query('SELECT icon_path, title FROM industries') as $row) {
    backfillPath($pdo, $row['icon_path'], (string) $row['title']);
}

foreach ($pdo->query('SELECT image_path, name FROM team_members') as $row) {
    backfillPath($pdo, $row['image_path'], (string) $row['name']);
}

foreach ($pdo->query('SELECT featured_image, title FROM blog_posts') as $row) {
    backfillPath($pdo, $row['featured_image'], (string) $row['title']);
}

foreach ($pdo->query('SELECT image_path, heading FROM page_blocks') as $row) {
    backfillPath($pdo, $row['image_path'], (string) $row['heading']);
}

foreach ($pdo->query('SELECT image_path, name FROM office_locations') as $row) {
    backfillPath($pdo, $row['image_path'], (string) $row['name']);
}

$siteLogo = $pdo->query("SELECT `value` FROM settings WHERE `key` = 'site_logo'")->fetchColumn();
backfillPath($pdo, $siteLogo !== false ? (string) $siteLogo : null, 'Site logo');

$ogImage = $pdo->query("SELECT `value` FROM settings WHERE `key` = 'default_og_image'")->fetchColumn();
backfillPath($pdo, $ogImage !== false ? (string) $ogImage : null, 'Default social share image');

echo "Backfill complete.\n";
