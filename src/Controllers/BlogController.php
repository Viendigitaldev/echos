<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Router;
use App\Http\View;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Page;
use App\Models\Setting;
use App\Services\AssetManager;
use App\Services\JsonLd;
use App\Services\SeoMetaService;

final class BlogController
{
    public function index(Request $request): void
    {
        AssetManager::enableBlogFilter();

        $page = Page::findBySlug('perspectives');
        $blocks = Page::blocksFor((int) $page['id']);
        $seo = SeoMetaService::resolve($page, Setting::get('site_name', 'Echos'));

        (new View())->render('blog/index', [
            'title' => $seo['title'],
            'metaDescription' => $seo['description'],
            'ogImage' => $seo['ogImage'],
            'breadcrumb' => $blocks['breadcrumb'],
            'posts' => BlogPost::publishedAll(),
            'categories' => BlogCategory::withPublishedCounts(),
            'jsonLd' => [JsonLd::breadcrumb([
                ['name' => 'Home', 'path' => '/'],
                ['name' => 'Perspectives', 'path' => '/perspectives'],
            ])],
        ]);
    }

    public function show(Request $request, string $slug): void
    {
        $post = BlogPost::findPublishedBySlug($slug);

        if ($post === null) {
            Router::renderNotFound($request->path());
            return;
        }

        $seo = SeoMetaService::resolve(
            ['seo_title' => $post['seo_title'], 'title' => $post['title'], 'seo_description' => $post['seo_description'] ?: $post['excerpt'], 'og_image' => $post['og_image'] ?: $post['featured_image']],
            Setting::get('site_name', 'Echos')
        );

        (new View())->render('blog/show', [
            'title' => $seo['title'],
            'metaDescription' => $seo['description'],
            'ogImage' => $seo['ogImage'],
            'post' => $post,
            'jsonLd' => [
                JsonLd::breadcrumb([
                    ['name' => 'Home', 'path' => '/'],
                    ['name' => 'Perspectives', 'path' => '/perspectives'],
                    ['name' => $post['title'], 'path' => '/perspectives/' . $post['slug']],
                ]),
                JsonLd::article($post),
            ],
        ]);
    }
}
