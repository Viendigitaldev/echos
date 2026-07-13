<?php
/** @var array<string, mixed>|null $item */
$action = $item === null ? url('/admin/application-categories') : url('/admin/application-categories/' . $item['id']);
?>
<div class="admin-card max-w-480">
    <form action="<?= e($action) ?>" method="post">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?= e($item['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="sort_order">Sort order</label>
            <input type="number" id="sort_order" name="sort_order" value="<?= e((string) ($item['sort_order'] ?? 0)) ?>">
            <div class="hint">Lower numbers appear first in the category tabs.</div>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="<?= e(url('/admin/application-categories')) ?>" class="btn">Cancel</a>
    </form>
</div>
