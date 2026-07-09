-- Recommended global option fields, added to the existing key-value settings
-- table. Safe to run against the existing echos_dev DB (idempotent).

USE echos_dev;

INSERT INTO settings (`key`, `value`) VALUES
    ('site_logo', ''),
    ('site_tagline', ''),
    ('contact_phone', ''),
    ('contact_address', ''),
    ('default_og_image', ''),
    ('google_site_verification', ''),
    ('ga_measurement_id', '')
ON DUPLICATE KEY UPDATE `value` = `value`;
