<?php
/** @var array<int, array<string, mixed>> $items */
?>
<div class="admin-actions">
    <a href="<?= e(url('/admin/industries/create')) ?>" class="btn btn-primary">+ New Industry</a>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead><tr><th>Icon</th><th>Title</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><img src="<?= e(url($item['icon_path'])) ?>" class="thumb-preview" alt=""></td>
            <td><?= e($item['title']) ?></td>
            <td><span class="badge badge-<?= e($item['status']) ?>"><?= e($item['status']) ?></span></td>
            <td class="row-actions">
                <a class="btn btn-sm" href="<?= e(url('/admin/industries/' . $item['id'] . '/edit')) ?>">Edit</a>
                <form action="<?= e(url('/admin/industries/' . $item['id'] . '/delete')) ?>" method="post" onsubmit="return confirm('Delete this industry?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if ($items === []): ?>
        <tr><td colspan="4" class="muted">No industries yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
