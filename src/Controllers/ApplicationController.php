<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\View;
use App\Models\ApplicationCategory;
use App\Models\ApplicationItem;
use App\Models\Page;
use App\Models\Setting;
use App\Services\AssetManager;
use App\Services\SeoMetaService;

final class ApplicationController
{
    public function index(Request $request): void
    {
        $page = Page::findBySlug('applications');
        $blocks = Page::blocksFor((int) $page['id']);
        $seo = SeoMetaService::resolve($page, Setting::get('site_name', 'Echos'));

        AssetManager::enableAppModal();
        AssetManager::enableTabFilter();

        (new View())->render('applications/index', [
            'title' => $seo['title'],
            'metaDescription' => $seo['description'],
            'ogImage' => $seo['ogImage'],
            'breadcrumb' => $blocks['breadcrumb'],
            'applications' => ApplicationItem::forApplicationsPage(),
            'categories' => ApplicationCategory::withPublishedCounts(),
        ]);
    }
}
