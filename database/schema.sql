-- Echos database schema
-- MySQL 8, InnoDB, utf8mb4. Run once against an empty `echos_dev` database.
-- Apply this and every file under migrations/ with the mysql CLI's charset
-- flag to avoid mojibaking any non-ASCII content:
--   mysql --default-character-set=utf8mb4 -u... -p... < schema.sql

CREATE DATABASE IF NOT EXISTS echos_dev
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE echos_dev;

-- ---------------------------------------------------------------------------
CREATE TABLE admin_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    must_change_password TINYINT(1) NOT NULL DEFAULT 1,
    last_login_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_admin_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
CREATE TABLE pages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL,
    seo_title VARCHAR(255) NULL,
    seo_description VARCHAR(500) NULL,
    og_image VARCHAR(255) NULL,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_pages_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
CREATE TABLE page_blocks (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_id INT UNSIGNED NOT NULL,
    block_key VARCHAR(100) NOT NULL,
    heading VARCHAR(255) NULL,
    subheading VARCHAR(500) NULL,
    body TEXT NULL,
    image_path VARCHAR(255) NULL,
    cta_label VARCHAR(100) NULL,
    cta_url VARCHAR(255) NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    UNIQUE KEY uq_page_blocks_page_key (page_id, block_key),
    CONSTRAINT fk_page_blocks_page FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
CREATE TABLE application_categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    UNIQUE KEY uq_application_categories_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
CREATE TABLE applications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    category_id INT UNSIGNED NULL,
    short_description VARCHAR(500) NULL,
    full_description TEXT NULL,
    image_path VARCHAR(255) NULL,
    show_on_home TINYINT(1) NOT NULL DEFAULT 0,
    show_on_applications_page TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    status ENUM('draft','published') NOT NULL DEFAULT 'published',
    seo_title VARCHAR(255) NULL,
    seo_description VARCHAR(500) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_applications_slug (slug),
    INDEX idx_applications_status_home (status, show_on_home),
    INDEX idx_applications_status_apps_page (status, show_on_applications_page),
    INDEX idx_applications_category (category_id),
    CONSTRAINT fk_applications_category FOREIGN KEY (category_id) REFERENCES application_categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
CREATE TABLE industries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    icon_path VARCHAR(255) NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    status ENUM('draft','published') NOT NULL DEFAULT 'published',
    INDEX idx_industries_status_sort (status, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
CREATE TABLE team_members (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    designation VARCHAR(150) NULL,
    bio TEXT NULL,
    image_path VARCHAR(255) NULL,
    linkedin_url VARCHAR(255) NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    status ENUM('draft','published') NOT NULL DEFAULT 'published',
    INDEX idx_team_members_status_sort (status, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
CREATE TABLE blog_categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    UNIQUE KEY uq_blog_categories_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
CREATE TABLE blog_posts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    excerpt VARCHAR(500) NULL,
    body MEDIUMTEXT NULL,
    featured_image VARCHAR(255) NULL,
    author_name VARCHAR(100) NULL,
    author_image VARCHAR(255) NULL,
    read_minutes SMALLINT UNSIGNED NULL,
    published_at DATETIME NULL,
    status ENUM('draft','published') NOT NULL DEFAULT 'draft',
    seo_title VARCHAR(255) NULL,
    seo_description VARCHAR(500) NULL,
    og_image VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_blog_posts_slug (slug),
    INDEX idx_blog_posts_status_published (status, published_at),
    CONSTRAINT fk_blog_posts_category FOREIGN KEY (category_id) REFERENCES blog_categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
CREATE TABLE contact_submissions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reason VARCHAR(100) NULL,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL,
    phone VARCHAR(50) NULL,
    company VARCHAR(150) NULL,
    message TEXT NOT NULL,
    ip_address VARCHAR(45) NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_contact_submissions_read_created (is_read, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
CREATE TABLE office_locations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    -- Drives the `{slug}-card` CSS hook in main.css (e.g. .india-card has a
    -- deliberate staggered-layout offset) — keep this in sync with any new
    -- location-specific rules added to main.css.
    slug VARCHAR(100) NOT NULL,
    image_path VARCHAR(255) NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    UNIQUE KEY uq_office_locations_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
CREATE TABLE menu_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(100) NOT NULL,
    url VARCHAR(255) NOT NULL,
    icon_class VARCHAR(60) NULL,
    parent_id INT UNSIGNED NULL,
    location ENUM('header','footer_left','footer_right') NOT NULL DEFAULT 'header',
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    INDEX idx_menu_items_location_sort (location, sort_order),
    CONSTRAINT fk_menu_items_parent FOREIGN KEY (parent_id) REFERENCES menu_items(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
CREATE TABLE settings (
    `key` VARCHAR(100) NOT NULL PRIMARY KEY,
    `value` TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Media library backing mandatory alt text + the admin's "choose from
-- library" picker. Keyed by path (looked up at render/admin time) rather
-- than FK'd from every content table — see media 008 migration.
CREATE TABLE media (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    path VARCHAR(255) NOT NULL,
    original_filename VARCHAR(255) NULL,
    mime_type VARCHAR(100) NULL,
    size_bytes INT UNSIGNED NULL,
    width SMALLINT UNSIGNED NULL,
    height SMALLINT UNSIGNED NULL,
    alt_text VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_media_path (path)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
