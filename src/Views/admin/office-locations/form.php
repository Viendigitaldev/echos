<?php
/** @var array<string, mixed>|null $item */
$action = $item === null ? url('/admin/office-locations') : url('/admin/office-locations/' . $item['id']);
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
                <label for="slug">CSS slug</label>
                <input type="text" id="slug" name="slug" value="<?= e($item['slug'] ?? '') ?>" placeholder="e.g. usa">
                <div class="hint">Drives the <code>.{slug}-card</code> class in main.css — only change this if you also update that CSS rule.</div>
            </div>
            <div class="form-group">
                <label for="sort_order">Sort order</label>
                <input type="number" id="sort_order" name="sort_order" value="<?= e((string) ($item['sort_order'] ?? 0)) ?>">
            </div>
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
        <div class="form-group">
            <div class="hint">Or provide an external image URL instead (bypasses the media library):</div>
            <input type="url" name="image_url" placeholder="https://...">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="<?= e(url('/admin/office-locations')) ?>" class="btn">Cancel</a>
    </form>
</div>
