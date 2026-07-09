<?php
/** @var array<string, mixed>|null $item */
$action = $item === null ? url('/admin/blog-categories') : url('/admin/blog-categories/' . $item['id']);
?>
<div class="admin-card max-w-480">
    <form action="<?= e($action) ?>" method="post">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?= e($item['name'] ?? '') ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="<?= e(url('/admin/blog-categories')) ?>" class="btn">Cancel</a>
    </form>
</div>
