<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Http\Request;
use App\Services\RobotsService;
use App\Services\SitemapGenerator;

final class SeoController extends AdminController
{
    public function index(Request $request): void
    {
        $this->render('admin/seo/index', [
            'pageTitle' => 'SEO Tools',
            'robotsContent' => RobotsService::content(),
            'sitemapUrl' => url('/sitemap.xml'),
            'sitemapGeneratedAt' => file_exists(dirname(__DIR__, 3) . '/public/sitemap.xml')
                ? date('d M Y H:i', (int) filemtime(dirname(__DIR__, 3) . '/public/sitemap.xml'))
                : null,
        ]);
    }

    public function saveRobots(Request $request): void
    {
        $this->requireCsrf($request);
        RobotsService::save((string) $request->input('robots_content', ''));
        $this->flash('success', 'robots.txt saved.');
        redirect('/admin/seo');
    }

    public function regenerateSitemap(Request $request): void
    {
        $this->requireCsrf($request);
        SitemapGenerator::regenerate();
        $this->flash('success', 'Sitemap regenerated.');
        redirect('/admin/seo');
    }
}
