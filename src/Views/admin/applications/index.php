<?php
/** @var array<int, array<string, mixed>> $items */
?>
<div class="admin-actions">
    <a href="<?= e(url('/admin/applications/create')) ?>" class="btn btn-primary">+ New Application</a>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead>
        <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Home</th>
            <th>Applications Page</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?= e($item['title']) ?></td>
            <td><?= e($item['category_name'] ?? '—') ?></td>
            <td><?= $item['show_on_home'] ? 'Yes' : '—' ?></td>
            <td><?= $item['show_on_applications_page'] ? 'Yes' : '—' ?></td>
            <td><span class="badge badge-<?= e($item['status']) ?>"><?= e($item['status']) ?></span></td>
            <td class="row-actions">
                <a class="btn btn-sm" href="<?= e(url('/admin/applications/' . $item['id'] . '/edit')) ?>">Edit</a>
                <form action="<?= e(url('/admin/applications/' . $item['id'] . '/delete')) ?>" method="post" onsubmit="return confirm('Delete this application?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if ($items === []): ?>
        <tr><td colspan="6" class="muted">No applications yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
