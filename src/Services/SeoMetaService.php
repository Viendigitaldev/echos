<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;

final class SeoMetaService
{
    /**
     * Resolves the effective title/description/OG image for a page, falling
     * back to site-wide defaults when a specific record has no override.
     *
     * @param array<string, mixed> $overrides
     * @return array{title: string, description: string, ogImage: string}
     */
    public static function resolve(array $overrides, string $siteName): array
    {
        $title = trim((string) ($overrides['seo_title'] ?? '')) ?: trim((string) ($overrides['title'] ?? '')) ?: $siteName;
        $description = trim((string) ($overrides['seo_description'] ?? ''));
        $ogImage = trim((string) ($overrides['og_image'] ?? $overrides['image_path'] ?? $overrides['featured_image'] ?? ''));

        if ($ogImage === '') {
            $ogImage = Setting::get('default_og_image');
        }

        return [
            'title' => $title,
            'description' => $description,
            'ogImage' => $ogImage,
        ];
    }
}
