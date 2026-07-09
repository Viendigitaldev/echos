-- Adds an optional icon_class column to menu_items so header dropdown
-- children can carry a FontAwesome icon (as the original static template
-- did for "About" and "Perspectives" under the Company dropdown).
-- Safe to run against the existing echos_dev DB.

ALTER TABLE menu_items
    ADD COLUMN icon_class VARCHAR(60) NULL AFTER url;

UPDATE menu_items SET icon_class = 'fa-light fa-layer-group' WHERE label = 'About' AND location = 'header';
UPDATE menu_items SET icon_class = 'fa-light fa-book-open' WHERE label = 'Perspectives' AND location = 'header';
