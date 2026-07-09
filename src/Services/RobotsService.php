<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use App\Support\Url;

final class RobotsService
{
    private const OUTPUT_PATH = __DIR__ . '/../../public/robots.txt';

    public static function content(): string
    {
        return Setting::get('robots_txt_content', "User-agent: *\nAllow: /\n");
    }

    public static function save(string $content): void
    {
        Setting::set('robots_txt_content', $content);
        self::regenerateFile();
    }

    public static function regenerateFile(): void
    {
        $body = rtrim(self::content()) . "\n";
        $body .= 'Sitemap: ' . Url::absolute('/sitemap.xml') . "\n";

        file_put_contents(self::OUTPUT_PATH, $body);
    }
}
