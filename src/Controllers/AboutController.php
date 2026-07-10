<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\View;
use App\Models\Page;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Services\JsonLd;
use App\Services\SeoMetaService;

final class AboutController
{
    public function index(Request $request): void
    {
        $page = Page::findBySlug('about');
        $blocks = Page::blocksFor((int) $page['id']);
        $seo = SeoMetaService::resolve($page, Setting::get('site_name', 'Echos'));

        (new View())->render('about/index', [
            'title' => $seo['title'],
            'metaDescription' => $seo['description'],
            'ogImage' => $seo['ogImage'],
            'blocks' => $blocks,
            'team' => TeamMember::published(),
            'jsonLd' => [JsonLd::breadcrumb([
                ['name' => 'Home', 'path' => '/'],
                ['name' => 'About', 'path' => '/about'],
            ])],
        ]);
    }
}
