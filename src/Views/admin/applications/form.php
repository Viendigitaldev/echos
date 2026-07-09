<?php
/**
 * @var array<string, mixed>|null $item
 * @var array<int, array<string, mixed>> $categories
 */
$action = $item === null ? url('/admin/applications') : url('/admin/applications/' . $item['id']);
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
                <label for="sort_order">Sort order</label>
                <input type="number" id="sort_order" name="sort_order" value="<?= e((string) ($item['sort_order'] ?? 0)) ?>">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="published" <?= ($item['status'] ?? 'published') === 'published' ? 'selected' : '' ?>>Published</option>
                    <option value="draft" <?= ($item['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="short_description">Short description (card summary)</label>
            <textarea id="short_description" name="short_description"><?= e($item['short_description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="full_description">Full description (modal detail)</label>
            <textarea id="full_description" name="full_description" class="tall"><?= e($item['full_description'] ?? '') ?></textarea>
        </div>

        <?php
        $fileFieldName = 'image';
        $altFieldName = 'alt_text';
        $existingFieldName = 'existing_media_id';
        $currentPath = $item['image_path'] ?? null;
        $required = $item === null;
        $label = 'Image';
        require __DIR__ . '/../partials/media-field.php';
        ?>

        <div class="form-group checkbox-group">
            <label><input type="checkbox" name="show_on_home" value="1" <?= !empty($item['show_on_home']) ? 'checked' : '' ?>> Show in homepage "Thinking Systems at Work" slider</label>
            <label><input type="checkbox" name="show_on_applications_page" value="1" <?= ($item === null || !empty($item['show_on_applications_page'])) ? 'checked' : '' ?>> Show on /applications page</label>
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
        <a href="<?= e(url('/admin/applications')) ?>" class="btn">Cancel</a>
    </form>
</div>
