<?php
/** @var array<string, mixed>|null $item */
$action = $item === null ? url('/admin/team') : url('/admin/team/' . $item['id']);
?>
<div class="admin-card">
    <form action="<?= e($action) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="form-row">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?= e($item['name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="designation">Designation</label>
                <input type="text" id="designation" name="designation" value="<?= e($item['designation'] ?? '') ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" class="tall"><?= e($item['bio'] ?? '') ?></textarea>
        </div>
        <?php
        $fileFieldName = 'image';
        $altFieldName = 'alt_text';
        $existingFieldName = 'existing_media_id';
        $currentPath = $item['image_path'] ?? null;
        $required = $item === null;
        $label = 'Photo';
        require __DIR__ . '/../partials/media-field.php';
        ?>
        <div class="form-row">
            <div class="form-group">
                <label for="linkedin_url">LinkedIn URL</label>
                <input type="text" id="linkedin_url" name="linkedin_url" value="<?= e($item['linkedin_url'] ?? '#') ?>">
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
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="<?= e(url('/admin/team')) ?>" class="btn">Cancel</a>
    </form>
</div>
