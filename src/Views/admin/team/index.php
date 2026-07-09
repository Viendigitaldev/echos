<?php
/** @var array<int, array<string, mixed>> $items */
?>
<div class="admin-actions">
    <a href="<?= e(url('/admin/team/create')) ?>" class="btn btn-primary">+ New Team Member</a>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead><tr><th>Photo</th><th>Name</th><th>Designation</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><img src="<?= e(url($item['image_path'])) ?>" class="thumb-preview" alt=""></td>
            <td><?= e($item['name']) ?></td>
            <td><?= e($item['designation']) ?></td>
            <td><span class="badge badge-<?= e($item['status']) ?>"><?= e($item['status']) ?></span></td>
            <td class="row-actions">
                <a class="btn btn-sm" href="<?= e(url('/admin/team/' . $item['id'] . '/edit')) ?>">Edit</a>
                <form action="<?= e(url('/admin/team/' . $item['id'] . '/delete')) ?>" method="post" onsubmit="return confirm('Remove this team member?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if ($items === []): ?>
        <tr><td colspan="5" class="muted">No team members yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
