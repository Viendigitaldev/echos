-- Additive indexes for query patterns that filter/sort by these columns
-- (WHERE status = ..., WHERE show_on_home = ..., ORDER BY published_at, etc.)
-- but had no supporting index. Safe to run against the existing echos_dev DB.

USE echos_dev;

ALTER TABLE applications
    ADD INDEX idx_applications_status_home (status, show_on_home),
    ADD INDEX idx_applications_status_apps_page (status, show_on_applications_page);

ALTER TABLE industries
    ADD INDEX idx_industries_status_sort (status, sort_order);

ALTER TABLE team_members
    ADD INDEX idx_team_members_status_sort (status, sort_order);

ALTER TABLE blog_posts
    ADD INDEX idx_blog_posts_status_published (status, published_at);

ALTER TABLE menu_items
    ADD INDEX idx_menu_items_location_sort (location, sort_order);

ALTER TABLE contact_submissions
    ADD INDEX idx_contact_submissions_read_created (is_read, created_at);
