-- Adds a 'thank-you' page + content block, following the same page_blocks
-- pattern as every other page — editable via the existing generic
-- /admin/pages/thank-you/edit screen with no admin code changes needed.
-- Safe to run against the existing echos_dev DB.
--
-- IMPORTANT: this file contains a non-ASCII em dash. Apply it with
--   mysql --default-character-set=utf8mb4 ... < 004_add_thank_you_page.sql
-- Running it without that flag silently mojibakes the em dash (the mysql
-- CLI's default client charset isn't utf8mb4 unless told).

USE echos_dev;

INSERT INTO pages (slug, seo_title, seo_description)
SELECT 'thank-you', 'Thank You — Echos', 'Thanks for reaching out to Echos.'
WHERE NOT EXISTS (SELECT 1 FROM pages WHERE slug = 'thank-you');

INSERT INTO page_blocks (page_id, block_key, heading, body, cta_label, cta_url, sort_order)
SELECT p.id, 'content', 'Thanks for reaching out.',
       "We've received your message and a member of the Echos team will be in touch shortly.",
       'Back to Home', '/', 1
FROM pages p
WHERE p.slug = 'thank-you'
  AND NOT EXISTS (
      SELECT 1 FROM page_blocks pb WHERE pb.page_id = p.id AND pb.block_key = 'content'
  );
