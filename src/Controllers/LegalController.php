<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\View;
use App\Models\Page;
use App\Models\Setting;
use App\Services\JsonLd;
use App\Services\SeoMetaService;

final class LegalController
{
    public function terms(Request $request): void
    {
        $this->renderLegalPage('terms-of-service');
    }

    public function privacyPolicy(Request $request): void
    {
        $this->renderLegalPage('privacy-policy');
    }

    private function renderLegalPage(string $slug): void
    {
        $page = Page::findBySlug($slug);
        $blocks = Page::blocksFor((int) $page['id']);
        $seo = SeoMetaService::resolve($page, Setting::get('site_name', 'Echos'));
        $crumbLabel = $blocks['content']['heading'] ?: ucwords(str_replace('-', ' ', $slug));

        (new View())->render('legal/show', [
            'title' => $seo['title'],
            'metaDescription' => $seo['description'],
            'ogImage' => $seo['ogImage'],
            'block' => $blocks['content'],
            'jsonLd' => [JsonLd::breadcrumb([
                ['name' => 'Home', 'path' => '/'],
                ['name' => $crumbLabel, 'path' => '/' . $slug],
            ])],
        ]);
    }
}
