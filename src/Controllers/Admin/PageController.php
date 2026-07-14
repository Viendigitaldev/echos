<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Http\Request;
use App\Models\Page;
use App\Models\PageBlock;
use App\Services\SitemapGenerator;
use App\Services\SlugService;

final class PageController extends AdminController
{
    public function index(Request $request): void
    {
        $this->render('admin/pages/index', [
            'pageTitle' => 'Pages & Sections',
            'items' => Page::all('slug ASC'),
        ]);
    }

    public function create(Request $request): void
    {
        $this->render('admin/pages/create', [
            'pageTitle' => 'New Page',
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireCsrf($request);

        $title = $request->trimmedInput('title');
        if ($title === '') {
            $this->flash('error', 'Page title is required.');
            redirect('/admin/pages/create');
        }

        $slug = SlugService::unique('pages', 'slug', $title);

        $pageId = Page::insert([
            'slug' => $slug,
            'is_custom' => 1,
            'seo_title' => $title,
            'seo_description' => '',
        ]);

        PageBlock::insert([
            'page_id' => $pageId,
            'block_key' => 'content',
            'heading' => $title,
            'subheading' => null,
            'body' => '',
            'sort_order' => 0,
        ]);

        SitemapGenerator::regenerate();
        $this->flash('success', 'Page created — add its content below.');
        redirect('/admin/pages/' . $slug . '/edit');
    }

    public function edit(Request $request, string $slug): void
    {
        $page = Page::findBySlug($slug);
        if ($page === null) {
            $this->flash('error', 'Page not found.');
            redirect('/admin/pages');
        }

        $this->render('admin/pages/edit', [
            'pageTitle' => 'Edit Page: ' . ucfirst($slug),
            'page' => $page,
            'blocks' => Page::blocksFor((int) $page['id']),
        ]);
    }

    public function delete(Request $request, string $slug): void
    {
        $this->requireCsrf($request);

        $page = Page::findBySlug($slug);
        if ($page === null) {
            $this->flash('error', 'Page not found.');
            redirect('/admin/pages');
        }

        if ((int) $page['is_custom'] !== 1) {
            $this->flash('error', 'This page is part of the site structure and cannot be deleted.');
            redirect('/admin/pages');
        }

        Page::delete((int) $page['id']);
        SitemapGenerator::regenerate();
        $this->flash('success', 'Page deleted.');
        redirect('/admin/pages');
    }

    public function update(Request $request, string $slug): void
    {
        $this->requireCsrf($request);

        $page = Page::findBySlug($slug);
        if ($page === null) {
            $this->flash('error', 'Page not found.');
            redirect('/admin/pages');
        }

        $data = [
            'seo_title' => $request->trimmedInput('seo_title'),
            'seo_description' => $request->trimmedInput('seo_description'),
        ];

        // Only custom pages may have their slug changed — the 8 structural
        // pages have theirs hardwired into routes.php/controllers, so
        // editing it here would just 404 the page.
        if ((int) $page['is_custom'] === 1) {
            $newSlug = SlugService::slugify($request->trimmedInput('slug'));
            if ($newSlug !== '' && $newSlug !== $slug) {
                $data['slug'] = SlugService::unique('pages', 'slug', $newSlug, (int) $page['id']);
            }
        }

        Page::update((int) $page['id'], $data);
        $slug = $data['slug'] ?? $slug;

        $blocks = $request->input('blocks', []);
        $files = $_FILES['blocks'] ?? [];

        foreach ($blocks as $blockKey => $fields) {
            $existing = PageBlock::findWhere(['page_id' => $page['id'], 'block_key' => $blockKey]);
            if ($existing === null) {
                continue;
            }

            $data = [
                'heading' => trim((string) ($fields['heading'] ?? '')),
                'subheading' => trim((string) ($fields['subheading'] ?? '')),
                'body' => trim((string) ($fields['body'] ?? '')),
                'cta_label' => trim((string) ($fields['cta_label'] ?? '')),
                'cta_url' => trim((string) ($fields['cta_url'] ?? '')),
            ];

            $fileEntry = $this->extractFileEntry($files, $blockKey);
            if ($fileEntry !== null || !empty($fields['existing_media_id'])) {
                try {
                    $resolved = $this->resolveImage(
                        $fileEntry ?? [],
                        trim((string) ($fields['alt_text'] ?? '')),
                        $fields['existing_media_id'] ?? null
                    );
                } catch (\RuntimeException $e) {
                    $this->flash('error', $e->getMessage());
                    redirect('/admin/pages/' . $slug . '/edit');
                }
                if ($resolved !== null) {
                    $data['image_path'] = $resolved;
                }
            }

            PageBlock::update((int) $existing['id'], $data);
        }

        SitemapGenerator::regenerate();
        $this->flash('success', 'Page updated.');
        redirect('/admin/pages/' . $slug . '/edit');
    }

    /**
     * $_FILES nests multi-dimensional field names under each of its own keys
     * (name/type/tmp_name/error/size), so blocks[key][image] arrives as
     * $_FILES['blocks']['error']['key']['image'] rather than a flat array —
     * this reassembles a single-file-shaped array for UploadService.
     *
     * @param array<string, mixed> $files
     * @return array<string, mixed>|null
     */
    private function extractFileEntry(array $files, string $blockKey): ?array
    {
        if (!isset($files['error'][$blockKey]['image'])) {
            return null;
        }

        return [
            'name' => $files['name'][$blockKey]['image'],
            'type' => $files['type'][$blockKey]['image'],
            'tmp_name' => $files['tmp_name'][$blockKey]['image'],
            'error' => $files['error'][$blockKey]['image'],
            'size' => $files['size'][$blockKey]['image'],
        ];
    }
}
