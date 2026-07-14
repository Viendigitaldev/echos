<?php
/** No variables required. */
?>
<div class="admin-card max-w-480">
    <form action="<?= e(url('/admin/pages')) ?>" method="post">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="title">Page title</label>
            <input type="text" id="title" name="title" required>
            <div class="hint">Used as the page heading and to generate its URL — both stay editable after creating it.</div>
        </div>
        <button type="submit" class="btn btn-primary">Create Page</button>
        <a href="<?= e(url('/admin/pages')) ?>" class="btn">Cancel</a>
    </form>
</div>
