<?php

declare(strict_types=1);

namespace App\Models;

final class MenuItem extends Model
{
    protected static string $table = 'menu_items';

    /** @var array<int, array<string, mixed>>|null request-scoped cache — one query serves header + both footer columns */
    private static ?array $cache = null;

    /**
     * Header items with their children nested under `children`, in sort order.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function headerTree(): array
    {
        return static::tree('header');
    }

    /** @return array<int, array<string, mixed>> */
    public static function footerLeft(): array
    {
        return static::byLocation('footer_left');
    }

    /** @return array<int, array<string, mixed>> */
    public static function footerRight(): array
    {
        return static::byLocation('footer_right');
    }

    /** @return array<int, array<string, mixed>> */
    private static function byLocation(string $location): array
    {
        return array_values(array_filter(
            static::allCached(),
            static fn (array $item): bool => $item['location'] === $location
        ));
    }

    /** @return array<int, array<string, mixed>> */
    private static function tree(string $location): array
    {
        $all = static::byLocation($location);

        $byParent = [];
        foreach ($all as $item) {
            $byParent[$item['parent_id'] ?? 0][] = $item;
        }

        $attachChildren = static function (array $item) use ($byParent, &$attachChildren): array {
            $item['children'] = $byParent[$item['id']] ?? [];
            $item['children'] = array_map($attachChildren, $item['children']);
            return $item;
        };

        return array_map($attachChildren, $byParent[0] ?? []);
    }

    /** @return array<int, array<string, mixed>> */
    private static function allCached(): array
    {
        if (self::$cache === null) {
            self::$cache = static::all('location ASC, sort_order ASC');
        }

        return self::$cache;
    }
}
