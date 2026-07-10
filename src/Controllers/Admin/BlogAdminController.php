<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Http\Request;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Redirect;
use App\Services\SitemapGenerator;
use App\Services\SlugService;

final class BlogAdminController extends AdminController
{
    public function index(Request $request): void
    {
        $this->render('admin/blog/index', [
            'pageTitle' => 'Blog Posts',
            'items' => BlogPost::all('created_at DESC'),
        ]);
    }

    public function create(Request $request): void
    {
        $this->render('admin/blog/form', [
            'pageTitle' => 'New Blog Post',
            'item' => null,
            'categories' => BlogCategory::all('name ASC'),
        ]);
    }

    public function edit(Request $request, string $id): void
    {
        $item = BlogPost::find((int) $id);
        if ($item === null) {
            $this->flash('error', 'Post not found.');
            redirect('/admin/blog');
        }
        $this->render('admin/blog/form', [
            'pageTitle' => 'Edit Blog Post',
            'item' => $item,
            'categories' => BlogCategory::all('name ASC'),
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireCsrf($request);
        $data = $this->collectFields($request);
        $data['slug'] = SlugService::unique('blog_posts', 'slug', $request->trimmedInput('title'));

        try {
            $image = $this->resolveImage($_FILES['featured_image'] ?? [], $request->trimmedInput('alt_text'), $request->input('existing_media_id'));
        } catch (\RuntimeException $e) {
            $this->flash('error', $e->getMessage());
            redirect('/admin/blog/create');
        }
        if ($image === null) {
            $this->flash('error', 'A featured image is required.');
            redirect('/admin/blog/create');
        }
        $data['featured_image'] = $image;

        BlogPost::insert($data);
        SitemapGenerator::regenerate();

        $this->flash('success', 'Post created.');
        redirect('/admin/blog');
    }

    public function update(Request $request, string $id): void
    {
        $this->requireCsrf($request);
        $item = BlogPost::find((int) $id);
        if ($item === null) {
            $this->flash('error', 'Post not found.');
            redirect('/admin/blog');
        }

        $data = $this->collectFields($request);
        if ($request->trimmedInput('title') !== $item['title']) {
            $data['slug'] = SlugService::unique('blog_posts', 'slug', $request->trimmedInput('title'), (int) $id);
        }

        try {
            $image = $this->resolveImage($_FILES['featured_image'] ?? [], $request->trimmedInput('alt_text'), $request->input('existing_media_id'));
        } catch (\RuntimeException $e) {
            $this->flash('error', $e->getMessage());
            redirect('/admin/blog/' . $id . '/edit');
        }
        if ($image !== null) {
            $data['featured_image'] = $image;
        }

        BlogPost::update((int) $id, $data);
        SitemapGenerator::regenerate();

        if (isset($data['slug'])) {
            Redirect::recordSlugChange('/perspectives/' . $item['slug'], '/perspectives/' . $data['slug']);
        }

        $this->flash('success', 'Post updated.');
        redirect('/admin/blog');
    }

    public function delete(Request $request, string $id): void
    {
        $this->requireCsrf($request);
        BlogPost::delete((int) $id);
        SitemapGenerator::regenerate();

        $this->flash('success', 'Post deleted.');
        redirect('/admin/blog');
    }

    /** @return array<string, mixed> */
    private function collectFields(Request $request): array
    {
        $categoryId = (int) $request->input('category_id', 0);
        $status = $request->input('status') === 'published' ? 'published' : 'draft';
        $publishedAt = $request->trimmedInput('published_at');

        return [
            'category_id' => $categoryId > 0 ? $categoryId : null,
            'title' => $request->trimmedInput('title'),
            'excerpt' => $request->trimmedInput('excerpt'),
            'body' => $request->input('body', ''),
            'author_name' => $request->trimmedInput('author_name', 'Team Echos'),
            'read_minutes' => (int) $request->input('read_minutes', 5),
            'published_at' => $publishedAt !== '' ? $publishedAt : ($status === 'published' ? date('Y-m-d H:i:s') : null),
            'status' => $status,
            'seo_title' => $request->trimmedInput('seo_title'),
            'seo_description' => $request->trimmedInput('seo_description'),
        ];
    }
}
