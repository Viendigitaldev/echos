<?php
/** @var array<int, array<string, mixed>> $items */
?>
<div class="admin-actions">
    <a href="<?= e(url('/admin/office-locations/create')) ?>" class="btn btn-primary">+ New Location</a>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead><tr><th>Image</th><th>Name</th><th>Slug</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><img src="<?= e(media_url($item['image_path'])) ?>" class="thumb-preview" alt=""></td>
            <td><?= e($item['name']) ?></td>
            <td><code><?= e($item['slug']) ?></code></td>
            <td class="row-actions">
                <a class="btn btn-sm" href="<?= e(url('/admin/office-locations/' . $item['id'] . '/edit')) ?>">Edit</a>
                <form action="<?= e(url('/admin/office-locations/' . $item['id'] . '/delete')) ?>" method="post" onsubmit="return confirm('Delete this location?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if ($items === []): ?>
        <tr><td colspan="4" class="muted">No office locations yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
