<?php
/**
 * @var array<string, mixed>|null $item
 * @var array<int, array<string, mixed>> $parents
 */
$action = $item === null ? url('/admin/menu') : url('/admin/menu/' . $item['id']);
?>
<div class="admin-card max-w-560">
    <form action="<?= e($action) ?>" method="post">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="label">Label</label>
            <input type="text" id="label" name="label" value="<?= e($item['label'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="url">URL</label>
            <input type="text" id="url" name="url" value="<?= e($item['url'] ?? '') ?>" placeholder="/about or #">
        </div>
        <div class="form-group">
            <label for="icon_class">Icon class (dropdown children only)</label>
            <input type="text" id="icon_class" name="icon_class" value="<?= e($item['icon_class'] ?? '') ?>" placeholder="fa-light fa-layer-group">
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="location">Location</label>
                <select id="location" name="location">
                    <?php foreach (['header' => 'Header', 'footer_left' => 'Footer (left)', 'footer_right' => 'Footer (right)'] as $value => $label): ?>
                    <option value="<?= e($value) ?>" <?= ($item['location'] ?? 'header') === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="parent_id">Parent (header dropdown only)</label>
                <select id="parent_id" name="parent_id">
                    <option value="">— Top level —</option>
                    <?php foreach ($parents as $parent): ?>
                        <?php if ($item !== null && (int) $parent['id'] === (int) $item['id']) continue; ?>
                    <option value="<?= (int) $parent['id'] ?>" <?= (int) ($item['parent_id'] ?? 0) === (int) $parent['id'] ? 'selected' : '' ?>><?= e($parent['label']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="sort_order">Sort order</label>
                <input type="number" id="sort_order" name="sort_order" value="<?= e((string) ($item['sort_order'] ?? 0)) ?>">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="<?= e(url('/admin/menu')) ?>" class="btn">Cancel</a>
    </form>
</div>
