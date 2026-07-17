-- Forgot-password support: a hashed reset token + expiry per admin user.
-- The raw token only ever exists in the emailed link; only its SHA-256
-- hash is stored, so a DB compromise alone can't be used to reset a
-- password. Run once against the existing echos_dev DB.

USE echos_dev;

ALTER TABLE admin_users
    ADD COLUMN password_reset_token_hash VARCHAR(64) NULL AFTER must_change_password,
    ADD COLUMN password_reset_expires_at DATETIME NULL AFTER password_reset_token_hash;
