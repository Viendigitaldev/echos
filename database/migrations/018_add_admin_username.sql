-- Separates the admin's login identifier (username) from their recovery
-- email (used only for forgot-password links). Previously the single
-- `email` column served both purposes, but the site's admin login isn't a
-- real deliverable address ("admin@echos"), making password recovery
-- useless. Backfills username = email so existing logins keep working
-- unchanged; the admin can then set a real email separately via the
-- Account page for password recovery to actually work.
-- Run once against the existing echos_dev DB.

USE echos_dev;

ALTER TABLE admin_users ADD COLUMN username VARCHAR(190) NULL AFTER name;
UPDATE admin_users SET username = email WHERE username IS NULL;
ALTER TABLE admin_users MODIFY COLUMN username VARCHAR(190) NOT NULL;
ALTER TABLE admin_users ADD UNIQUE KEY uq_admin_users_username (username);
