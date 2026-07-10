<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use App\Support\Url;

final class JsonLd
{
    /** @return array<string, mixed> */
    public static function organization(): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => Setting::get('site_name', 'Echos'),
            'url' => Url::absolute('/'),
        ];

        $logo = Setting::get('site_logo');
        if ($logo !== '') {
            $schema['logo'] = Url::absolute($logo);
        }

        $tagline = Setting::get('site_tagline');
        if ($tagline !== '') {
            $schema['description'] = $tagline;
        }

        $phone = Setting::get('contact_phone');
        if ($phone !== '') {
            $schema['telephone'] = $phone;
        }

        $address = Setting::get('contact_address');
        if ($address !== '') {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $address,
            ];
        }

        $sameAs = array_values(array_filter([
            Setting::get('social_x_url'),
            Setting::get('social_linkedin_url'),
        ], static fn (string $url): bool => $url !== '' && $url !== '#'));

        if ($sameAs !== []) {
            $schema['sameAs'] = $sameAs;
        }

        return $schema;
    }

    /**
     * @param array<int, array{name: string, path?: string|null}> $items ordered Home -> ... -> current page
     * @return array<string, mixed>
     */
    public static function breadcrumb(array $items): array
    {
        $position = 1;
        $elements = [];
        foreach ($items as $item) {
            $entry = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $item['name'],
            ];
            if (!empty($item['path'])) {
                $entry['item'] = Url::absolute($item['path']);
            }
            $elements[] = $entry;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $elements,
        ];
    }

    /**
     * @param array<string, mixed> $post
     * @return array<string, mixed>
     */
    public static function article(array $post): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $post['title'],
            'description' => $post['excerpt'] ?? '',
            'author' => [
                '@type' => 'Person',
                'name' => $post['author_name'] ?: Setting::get('site_name', 'Echos'),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => Setting::get('site_name', 'Echos'),
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => Url::absolute('/perspectives/' . $post['slug']),
            ],
        ];

        if (!empty($post['published_at'])) {
            $schema['datePublished'] = date('c', strtotime((string) $post['published_at']));
        }

        if (!empty($post['featured_image'])) {
            $schema['image'] = Url::absolute($post['featured_image']);
        }

        $logo = Setting::get('site_logo');
        if ($logo !== '') {
            $schema['publisher']['logo'] = [
                '@type' => 'ImageObject',
                'url' => Url::absolute($logo),
            ];
        }

        return $schema;
    }

    /**
     * @param array<int, array<string, mixed>> $schemas
     */
    public static function render(array $schemas): string
    {
        $html = '';
        foreach ($schemas as $schema) {
            if ($schema === []) {
                continue;
            }

            // Default json_encode() escapes forward slashes, which keeps a
            // malicious "</script>" inside any string value (e.g. a post
            // title) from breaking out of this script tag.
            $json = json_encode($schema, JSON_UNESCAPED_UNICODE);
            $html .= '<script type="application/ld+json">' . $json . '</script>' . "\n";
        }

        return $html;
    }
}
