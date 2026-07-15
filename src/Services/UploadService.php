<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Media;

final class UploadService
{
    // SVG is deliberately excluded: it can carry embedded <script>, and none
    // of this site's imagery needs vector icons beyond what already ships in
    // the static theme assets.
    private const ALLOWED_MIME = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    private const MAX_BYTES = 5 * 1024 * 1024; // 5MB

    /**
     * @param array<string, mixed> $file One entry from $_FILES
     * @return array{path: string, media_id: int}|null Null if no file was uploaded.
     * @throws \RuntimeException on validation failure, including missing alt text
     */
    public static function store(array $file, string $altText): ?array
    {
        if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Upload failed (error code ' . $file['error'] . ').');
        }

        $altText = trim($altText);
        if ($altText === '') {
            throw new \RuntimeException('Alt text is required for every image upload.');
        }

        if ($file['size'] > self::MAX_BYTES) {
            throw new \RuntimeException('File is too large (max 5MB).');
        }

        $mime = mime_content_type($file['tmp_name']);
        if ($mime === false || !isset(self::ALLOWED_MIME[$mime])) {
            throw new \RuntimeException('Unsupported file type. Allowed: JPG, PNG, WEBP, SVG.');
        }

        $extension = self::ALLOWED_MIME[$mime];
        $relativeDir = 'uploads/' . date('Y') . '/' . date('m');
        $absoluteDir = dirname(__DIR__, 2) . '/storage/' . $relativeDir;

        if (!is_dir($absoluteDir) && !mkdir($absoluteDir, 0755, true) && !is_dir($absoluteDir)) {
            throw new \RuntimeException('Could not create upload directory.');
        }

        $baseSlug = SlugService::slugify(pathinfo((string) ($file['name'] ?? ''), PATHINFO_FILENAME));
        if ($baseSlug === '') {
            $baseSlug = 'image';
        }

        $filename = $baseSlug . '.' . $extension;
        $suffix = 2;
        while (file_exists($absoluteDir . '/' . $filename)) {
            $filename = $baseSlug . '-' . $suffix . '.' . $extension;
            $suffix++;
        }

        $destination = $absoluteDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new \RuntimeException('Could not save uploaded file.');
        }

        $path = $relativeDir . '/' . $filename;
        $dimensions = @getimagesize($destination);

        $media = Media::create(
            $path,
            (string) ($file['name'] ?? $filename),
            $mime,
            (int) $file['size'],
            $dimensions !== false ? $dimensions[0] : null,
            $dimensions !== false ? $dimensions[1] : null,
            $altText
        );

        return ['path' => $path, 'media_id' => (int) $media['id']];
    }
}
