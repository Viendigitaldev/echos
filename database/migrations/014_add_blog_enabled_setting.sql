-- Lets the manager temporarily hide the Perspectives (Blog) section from
-- an admin Settings checkbox instead of needing code changes: when '0',
-- BlogController 404s /perspectives and /perspectives/{slug}, the nav links
-- and homepage teaser are hidden, and the sitemap excludes those URLs.
-- Defaults to disabled ('0') — client content isn't ready yet.
-- Safe to run against the existing echos_dev DB (idempotent).

USE echos_dev;

INSERT INTO settings (`key`, `value`) VALUES ('blog_enabled', '0')
ON DUPLICATE KEY UPDATE `value` = `value`;
