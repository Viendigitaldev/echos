<?php
/** @var array<int, array<string, mixed>> $items */
$byId = [];
foreach ($items as $i) { $byId[$i['id']] = $i; }
?>
<div class="admin-actions">
    <a href="<?= e(url('/admin/menu/create')) ?>" class="btn btn-primary">+ New Menu Item</a>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead><tr><th>Label</th><th>URL</th><th>Location</th><th>Parent</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?= e($item['label']) ?></td>
            <td><code><?= e($item['url']) ?></code></td>
            <td><?= e($item['location']) ?></td>
            <td><?= $item['parent_id'] ? e($byId[$item['parent_id']]['label'] ?? '—') : '—' ?></td>
            <td class="row-actions">
                <a class="btn btn-sm" href="<?= e(url('/admin/menu/' . $item['id'] . '/edit')) ?>">Edit</a>
                <form action="<?= e(url('/admin/menu/' . $item['id'] . '/delete')) ?>" method="post" onsubmit="return confirm('Delete this menu item?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if ($items === []): ?>
        <tr><td colspan="5" class="muted">No menu items yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
