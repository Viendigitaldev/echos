<?php

declare(strict_types=1);

namespace App\Models;

final class OfficeLocation extends Model
{
    protected static string $table = 'office_locations';

    /** @return array<int, array<string, mixed>> */
    public static function ordered(): array
    {
        return static::all('sort_order ASC');
    }
}
