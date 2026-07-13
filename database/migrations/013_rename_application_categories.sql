-- Renames the 4 application categories from the generic capability names
-- they were seeded with to the industries they actually represent, and adds
-- a sort_order column so the display order is a deliberate choice (manager-
-- specified: Insurance, Banking, Manufacturing, Compliance) rather than
-- alphabetical. Safe to run against the existing echos_dev DB.

USE echos_dev;

ALTER TABLE application_categories
    ADD COLUMN sort_order INT UNSIGNED NOT NULL DEFAULT 0 AFTER slug;

UPDATE application_categories SET name = 'Insurance', slug = 'insurance', sort_order = 1 WHERE name = 'Claims';
UPDATE application_categories SET name = 'Banking', slug = 'banking', sort_order = 2 WHERE name = 'Relationship';
UPDATE application_categories SET name = 'Manufacturing', slug = 'manufacturing', sort_order = 3 WHERE name = 'Supply Chain';
UPDATE application_categories SET name = 'Compliance', slug = 'compliance', sort_order = 4 WHERE name = 'Intelligence';
