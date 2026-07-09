<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\View;
use App\Models\ApplicationItem;
use App\Models\BlogPost;
use App\Models\Industry;
use App\Models\Page;
use App\Models\Setting;
use App\Services\AssetManager;
use App\Services\SeoMetaService;

final class HomeController
{
    public function index(Request $request): void
    {
        $page = Page::findBySlug('home');
        $blocks = Page::blocksFor((int) $page['id']);
        $seo = SeoMetaService::resolve($page, Setting::get('site_name', 'Echos'));

        AssetManager::enableSwiper();
        AssetManager::enableRevealText();
        AssetManager::enableAppModal();

        (new View())->render('home/index', [
            'title' => $seo['title'],
            'metaDescription' => $seo['description'],
            'ogImage' => $seo['ogImage'],
            'blocks' => $blocks,
            'featured' => ApplicationItem::forHomeSlider(),
            'industries' => Industry::published(),
            'latestPosts' => BlogPost::latestPublished(2),
        ]);
    }
}
