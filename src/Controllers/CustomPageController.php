<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Router;
use App\Http\View;
use App\Models\Page;
use App\Models\Setting;
use App\Services\JsonLd;
use App\Services\SeoMetaService;

final class CustomPageController
{
    public function show(Request $request, string $slug): void
    {
        $page = Page::findBySlug($slug);
        if ($page === null || (int) $page['is_custom'] !== 1) {
            Router::renderNotFound($request->path());
            return;
        }

        $block = Page::blocksFor((int) $page['id'])['content'] ?? null;
        if ($block === null) {
            Router::renderNotFound($request->path());
            return;
        }

        $seo = SeoMetaService::resolve($page, Setting::get('site_name', 'Echos'));
        $crumbLabel = $block['heading'] ?: ucwords(str_replace('-', ' ', $slug));

        (new View())->render('legal/show', [
            'title' => $seo['title'],
            'metaDescription' => $seo['description'],
            'ogImage' => $seo['ogImage'],
            'block' => $block,
            'jsonLd' => [JsonLd::breadcrumb([
                ['name' => 'Home', 'path' => '/'],
                ['name' => $crumbLabel, 'path' => '/' . $slug],
            ])],
        ]);
    }
}
