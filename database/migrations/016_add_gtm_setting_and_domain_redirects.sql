-- Domain cutover from the old HubSpot-hosted echo-s.ai site to this app:
-- 1. Seeds the empty gtm_container_id setting (admin fills in the real
--    GTM-XXXXXXX value at /admin/settings to keep existing tag/pixel
--    tracking continuous across the switch).
-- 2. Preserves the old site's indexed/bookmarked URLs with a 301 to their
--    closest equivalent here, so search rankings and backlinks survive
--    the cutover instead of 404ing.
-- Safe to run against the existing echos_dev DB (idempotent).

USE echos_dev;

INSERT INTO settings (`key`, `value`) VALUES ('gtm_container_id', '')
ON DUPLICATE KEY UPDATE `value` = `value`;

INSERT INTO redirects (from_path, to_path) VALUES
    ('/about-us', '/about'),
    ('/contact-us', '/contact'),
    ('/deep-tech-implementations', '/applications')
ON DUPLICATE KEY UPDATE to_path = VALUES(to_path);
