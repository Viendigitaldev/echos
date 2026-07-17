<?php $pageTitle = 'Account'; ?>
<div class="admin-card max-w-480">
    <h3 class="mt-0">Username</h3>
    <p class="muted">Used to log in — this is not an email address.</p>
    <form action="<?= e(url('/admin/account/username')) ?>" method="post">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?= e($currentUser['username']) ?>" required>
        </div>
        <div class="form-group">
            <label for="current_password_username">Current password</label>
            <input type="password" id="current_password_username" name="current_password" required>
            <div class="hint">Required to confirm it's really you before changing the username.</div>
        </div>
        <button type="submit" class="btn btn-primary">Update Username</button>
    </form>
</div>

<div class="admin-card max-w-480">
    <h3 class="mt-0">Recovery email</h3>
    <p class="muted">Where a password reset link is sent if you use "Forgot password?" — not used to log in.</p>
    <form action="<?= e(url('/admin/account/email')) ?>" method="post">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= e($currentUser['email']) ?>" required>
        </div>
        <div class="form-group">
            <label for="current_password_email">Current password</label>
            <input type="password" id="current_password_email" name="current_password" required>
            <div class="hint">Required to confirm it's really you before changing the recovery email.</div>
        </div>
        <button type="submit" class="btn btn-primary">Update Email</button>
    </form>
</div>

<div class="admin-card max-w-480">
    <h3 class="mt-0">Password</h3>
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
