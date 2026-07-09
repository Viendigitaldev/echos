<?php

declare(strict_types=1);

namespace App\Services;

use App\Database\Connection;
use App\Support\Url;

final class SitemapGenerator
{
    private const OUTPUT_PATH = __DIR__ . '/../../public/sitemap.xml';

    public static function regenerate(): void
    {
        $urls = [];

        $urls[] = ['loc' => Url::absolute('/'), 'priority' => '1.0'];

        $pageSlugs = ['about' => '0.8', 'applications' => '0.8', 'perspectives' => '0.6', 'contact' => '0.5'];
        foreach ($pageSlugs as $slug => $priority) {
            $urls[] = ['loc' => Url::absolute('/' . $slug), 'priority' => $priority];
        }

        // Individual applications only exist as a JS modal on the /applications
        // page (no unique server-rendered URL per item), so they are not
        // listed separately here — that page already covers the content.

        $stmt = Connection::get()->query("SELECT slug, updated_at FROM blog_posts WHERE status = 'published'");
        foreach ($stmt->fetchAll() as $row) {
            $urls[] = ['loc' => Url::absolute('/perspectives/' . $row['slug']), 'priority' => '0.6', 'lastmod' => $row['updated_at']];
        }

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>');

        foreach ($urls as $url) {
            $node = $xml->addChild('url');
            $node->addChild('loc', htmlspecialchars($url['loc'], ENT_XML1));
            $node->addChild('priority', $url['priority']);
            if (!empty($url['lastmod'])) {
                $node->addChild('lastmod', date('Y-m-d', strtotime($url['lastmod'])));
            }
        }

        $xml->asXML(self::OUTPUT_PATH);
    }
}
