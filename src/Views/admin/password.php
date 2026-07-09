<?php $pageTitle = 'Change Password'; ?>
<div class="admin-card max-w-480">
    <?php if (!empty($currentUser['must_change_password'])): ?>
    <p class="muted">You're using a temporary password. Please set a new one to continue.</p>
    <?php endif; ?>
    <form action="<?= e(url('/admin/password')) ?>" method="post">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="password">New password</label>
            <input type="password" id="password" name="password" minlength="10" required>
            <div class="hint">At least 10 characters.</div>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm new password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" minlength="10" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Password</button>
    </form>
</div>
