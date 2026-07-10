<?php
/**
 * @var array<string, mixed> $page
 * @var array<string, array<string, mixed>> $blocks
 */

// Only the legal pages render their block body as raw HTML on the front
// end (src/Views/legal/show.php) — every other page's blocks are rendered
// as escaped plain text, so a rich editor there would be misleading.
$richTextPages = ['terms-of-service', 'privacy-policy'];
$hasRichEditor = in_array($page['slug'], $richTextPages, true);
?>
<div class="admin-card">
    <h3 class="mt-0">Page SEO</h3>
    <form action="<?= e(url('/admin/pages/' . $page['slug'])) ?>" method="post" enctype="multipart/form-data" id="page-form">
        <?= csrf_field() ?>
        <div class="form-row">
            <div class="form-group">
                <label for="seo_title">SEO title</label>
                <input type="text" id="seo_title" name="seo_title" value="<?= e($page['seo_title'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="seo_description">SEO description</label>
                <input type="text" id="seo_description" name="seo_description" value="<?= e($page['seo_description'] ?? '') ?>">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save SEO</button>
    </form>
</div>

<?php foreach ($blocks as $key => $block): ?>
<div class="admin-card">
    <h3 class="mt-0"><?= e(ucwords(str_replace('_', ' ', $key))) ?></h3>
    <form action="<?= e(url('/admin/pages/' . $page['slug'])) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="seo_title" value="<?= e($page['seo_title'] ?? '') ?>">
        <input type="hidden" name="seo_description" value="<?= e($page['seo_description'] ?? '') ?>">

        <div class="form-group">
            <label>Heading</label>
            <input type="text" name="blocks[<?= e($key) ?>][heading]" value="<?= e($block['heading'] ?? '') ?>">
        </div>
        <?php if ($block['subheading'] !== null): ?>
        <div class="form-group">
            <label>Subheading</label>
            <input type="text" name="blocks[<?= e($key) ?>][subheading]" value="<?= e($block['subheading'] ?? '') ?>">
        </div>
        <?php endif; ?>
        <?php if ($block['body'] !== null): ?>
        <div class="form-group">
            <label>Body</label>
            <textarea name="blocks[<?= e($key) ?>][body]" class="<?= $hasRichEditor ? 'tall' : '' ?>" <?= $hasRichEditor ? 'data-rich-editor' : '' ?>><?= e($block['body'] ?? '') ?></textarea>
        </div>
        <?php endif; ?>
        <?php if ($block['image_path'] !== null): ?>
        <?php
        $fileFieldName = 'blocks[' . $key . '][image]';
        $altFieldName = 'blocks[' . $key . '][alt_text]';
        $existingFieldName = 'blocks[' . $key . '][existing_media_id]';
        $currentPath = $block['image_path'];
        $required = false;
        $label = 'Image';
        require __DIR__ . '/../partials/media-field.php';
        ?>
        <?php endif; ?>
        <?php if ($block['cta_label'] !== null || $block['cta_url'] !== null): ?>
        <div class="form-row">
            <div class="form-group">
                <label>CTA label</label>
                <input type="text" name="blocks[<?= e($key) ?>][cta_label]" value="<?= e($block['cta_label'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>CTA URL</label>
                <input type="text" name="blocks[<?= e($key) ?>][cta_url]" value="<?= e($block['cta_url'] ?? '') ?>">
            </div>
        </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary">Save this section</button>
    </form>
</div>
<?php endforeach; ?>

<?php if ($hasRichEditor): ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css">
<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<script src="<?= e(asset('js/admin-rich-editor.js')) ?>"></script>
<?php endif; ?>
