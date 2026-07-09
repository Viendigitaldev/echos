<?php
/** @var array<int, array<string, mixed>> $items */
?>
<div class="admin-actions">
    <a href="<?= e(url('/admin/application-categories/create')) ?>" class="btn btn-primary">+ New Category</a>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead><tr><th>Name</th><th>Slug</th><th>Published applications</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?= e($item['name']) ?></td>
            <td><code><?= e($item['slug']) ?></code></td>
            <td><?= (int) $item['application_count'] ?></td>
            <td class="row-actions">
                <a class="btn btn-sm" href="<?= e(url('/admin/application-categories/' . $item['id'] . '/edit')) ?>">Edit</a>
                <form action="<?= e(url('/admin/application-categories/' . $item['id'] . '/delete')) ?>" method="post" onsubmit="return confirm('Delete this category? Applications in it will become uncategorized.');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if ($items === []): ?>
        <tr><td colspan="4" class="muted">No categories yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
