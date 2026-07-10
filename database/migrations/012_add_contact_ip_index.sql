-- Backs ContactSubmission::countRecentByIp(), used to rate-limit the public
-- contact form per IP. Safe to run against the existing echos_dev DB.

USE echos_dev;

ALTER TABLE contact_submissions
    ADD INDEX idx_contact_submissions_ip_created (ip_address, created_at);
