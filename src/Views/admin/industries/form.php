<?php
/** @var array<string, mixed>|null $item */
$action = $item === null ? url('/admin/industries') : url('/admin/industries/' . $item['id']);
?>
<div class="admin-card">
    <form action="<?= e($action) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?= e($item['title'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description"><?= e($item['description'] ?? '') ?></textarea>
        </div>
        <?php
        $fileFieldName = 'icon';
        $altFieldName = 'alt_text';
        $existingFieldName = 'existing_media_id';
        $currentPath = $item['icon_path'] ?? null;
        $required = $item === null;
        $label = 'Icon';
        require __DIR__ . '/../partials/media-field.php';
        ?>
        <div class="form-row">
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
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="<?= e(url('/admin/industries')) ?>" class="btn">Cancel</a>
    </form>
</div>
