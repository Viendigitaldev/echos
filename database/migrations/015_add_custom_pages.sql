-- Distinguishes admin-creatable "custom" pages (simple title + rich-text
-- body, rendered through the generic legal/show.php-style template, routed
-- via a catch-all /{slug}) from the 8 structural pages (home, about, etc.)
-- whose slugs are hardwired into routes.php/controllers and must never be
-- edited or deleted through this flag.
-- Safe to run against the existing echos_dev DB.

USE echos_dev;

ALTER TABLE pages
    ADD COLUMN is_custom TINYINT(1) NOT NULL DEFAULT 0 AFTER slug;
