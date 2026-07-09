-- SMTP settings, moved from .env into the admin-editable settings table so
-- the mailbox credentials can be entered/updated from the backend instead
-- of requiring server file access. Seeded with Hostinger's standard
-- outgoing-mail defaults (host/port/encryption) — username/password/from
-- address are left blank for the admin to fill in with the real mailbox.
-- Safe to run against the existing echos_dev DB (idempotent).

USE echos_dev;

INSERT INTO settings (`key`, `value`) VALUES
    ('mail_host', 'smtp.hostinger.com'),
    ('mail_port', '587'),
    ('mail_encryption', 'tls'),
    ('mail_username', ''),
    ('mail_password', ''),
    ('mail_from_address', ''),
    ('mail_from_name', 'Echos')
ON DUPLICATE KEY UPDATE `value` = `value`;
