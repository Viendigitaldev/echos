<?php

declare(strict_types=1);

namespace App\Models;

final class Page extends Model
{
    protected static string $table = 'pages';

    /** @return array<string, mixed>|null */
    public static function findBySlug(string $slug): ?array
    {
        return static::findWhere(['slug' => $slug]);
    }

    /**
     * Returns this page's blocks keyed by block_key for easy lookup in views.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function blocksFor(int $pageId): array
    {
        $blocks = PageBlock::allWhere(['page_id' => $pageId], 'sort_order ASC');

        $keyed = [];
        foreach ($blocks as $block) {
            $keyed[$block['block_key']] = $block;
        }

        return $keyed;
    }
}
