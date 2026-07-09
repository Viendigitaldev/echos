<?php

declare(strict_types=1);

namespace App\Models;

final class Industry extends Model
{
    protected static string $table = 'industries';

    /** @return array<int, array<string, mixed>> */
    public static function published(): array
    {
        return static::allWhere(['status' => 'published'], 'sort_order ASC');
    }
}
