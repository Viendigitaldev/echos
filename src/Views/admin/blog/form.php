<?php
/**
 * @var array<string, mixed>|null $item
 * @var array<int, array<string, mixed>> $categories
 */
$action = $item === null ? url('/admin/blog') : url('/admin/blog/' . $item['id']);
?>
<div class="admin-card">
    <form action="<?= e($action) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?= e($item['title'] ?? '') ?>" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id">
                    <option value="">— None —</option>
                    <?php foreach ($categories as $category): ?>
                    <option value="<?= (int) $category['id'] ?>" <?= (int) ($item['category_id'] ?? 0) === (int) $category['id'] ? 'selected' : '' ?>><?= e($category['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="author_name">Author</label>
                <input type="text" id="author_name" name="author_name" value="<?= e($item['author_name'] ?? 'Team Echos') ?>">
            </div>
            <div class="form-group">
                <label for="read_minutes">Read minutes</label>
                <input type="number" id="read_minutes" name="read_minutes" value="<?= e((string) ($item['read_minutes'] ?? 5)) ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="excerpt">Excerpt</label>
            <textarea id="excerpt" name="excerpt"><?= e($item['excerpt'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="body">Body</label>
            <textarea id="body" name="body" class="tall" data-rich-editor><?= e($item['body'] ?? '') ?></textarea>
            <div class="hint">Use Visual for normal editing, or switch to HTML to write markup directly. Rendered as-is on the post page.</div>
        </div>

        <?php
        $fileFieldName = 'featured_image';
        $altFieldName = 'alt_text';
        $existingFieldName = 'existing_media_id';
        $currentPath = $item['featured_image'] ?? null;
        $required = $item === null;
        $label = 'Featured image';
        require __DIR__ . '/../partials/media-field.php';
        ?>

        <div class="form-row">
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="draft" <?= ($item['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= ($item['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                </select>
            </div>
            <div class="form-group">
                <label for="published_at">Published at</label>
                <input type="datetime-local" id="published_at" name="published_at" value="<?= e(!empty($item['published_at']) ? str_replace(' ', 'T', substr((string) $item['published_at'], 0, 16)) : '') ?>">
                <div class="hint">Leave empty to use now when publishing.</div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="seo_title">SEO title</label>
                <input type="text" id="seo_title" name="seo_title" value="<?= e($item['seo_title'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="seo_description">SEO description</label>
                <input type="text" id="seo_description" name="seo_description" value="<?= e($item['seo_description'] ?? '') ?>">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="<?= e(url('/admin/blog')) ?>" class="btn">Cancel</a>
    </form>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css">
<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<script src="<?= e(asset('js/admin-rich-editor.js')) ?>"></script>
