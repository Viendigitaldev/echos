<?php

declare(strict_types=1);

namespace App\Models;

use App\Database\Connection;

final class Media extends Model
{
    protected static string $table = 'media';

    /**
     * Every content column that may hold a path pointing at a `media` row.
     * Used both to guard deletion and to resolve alt text by path.
     *
     * @var array<int, string>
     */
    private const REFERENCING_COLUMNS = [
        'applications.image_path',
        'industries.icon_path',
        'team_members.image_path',
        'blog_posts.featured_image',
        'page_blocks.image_path',
        'office_locations.image_path',
    ];

    /** @var array<string, string>|null request-scoped cache: path => alt_text */
    private static ?array $altTextCache = null;

    /** @return array<string, mixed> */
    public static function create(
        string $path,
        string $originalFilename,
        ?string $mimeType,
        int $sizeBytes,
        ?int $width,
        ?int $height,
        string $altText
    ): array {
        $id = static::insert([
            'path' => $path,
            'original_filename' => $originalFilename,
            'mime_type' => $mimeType,
            'size_bytes' => $sizeBytes,
            'width' => $width,
            'height' => $height,
            'alt_text' => $altText,
        ]);

        self::$altTextCache[$path] = $altText;

        return static::find($id);
    }

    /** @return array<string, mixed>|null */
    public static function findByPath(string $path): ?array
    {
        return static::findWhere(['path' => $path]);
    }

    public static function updateAltText(int $id, string $altText): void
    {
        static::update($id, ['alt_text' => $altText]);
        self::$altTextCache = null;
    }

    public static function delete(int $id): void
    {
        parent::delete($id);
        self::$altTextCache = null;
    }

    public static function isReferenced(string $path): bool
    {
        $pdo = Connection::get();

        foreach (self::REFERENCING_COLUMNS as $column) {
            [$table, $field] = explode('.', $column);
            $stmt = $pdo->prepare("SELECT 1 FROM {$table} WHERE {$field} = :path LIMIT 1");
            $stmt->execute(['path' => $path]);
            if ($stmt->fetchColumn() !== false) {
                return true;
            }
        }

        $stmt = $pdo->prepare("SELECT 1 FROM settings WHERE `value` = :path LIMIT 1");
        $stmt->execute(['path' => $path]);

        return $stmt->fetchColumn() !== false;
    }

    public static function altTextFor(?string $path, string $fallback = ''): string
    {
        $path = (string) $path;
        if ($path === '') {
            return $fallback;
        }

        self::loadAltTextCache();

        return self::$altTextCache[$path] ?? $fallback;
    }

    private static function loadAltTextCache(): void
    {
        if (self::$altTextCache !== null) {
            return;
        }

        self::$altTextCache = [];
        foreach (Connection::get()->query('SELECT path, alt_text FROM media') as $row) {
            self::$altTextCache[$row['path']] = $row['alt_text'];
        }
    }
}
