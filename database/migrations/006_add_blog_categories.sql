-- Adds "Research" and "Frontier" blog categories alongside the existing
-- "Engineering" one, so the /perspectives category filter has real options.
-- Safe to run against the existing echos_dev DB.

INSERT INTO blog_categories (name, slug)
SELECT 'Research', 'research'
WHERE NOT EXISTS (SELECT 1 FROM blog_categories WHERE slug = 'research');

INSERT INTO blog_categories (name, slug)
SELECT 'Frontier', 'frontier'
WHERE NOT EXISTS (SELECT 1 FROM blog_categories WHERE slug = 'frontier');
