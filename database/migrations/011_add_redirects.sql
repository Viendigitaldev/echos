-- Auto-populated whenever a blog post's slug changes (title edit), so an old
-- bookmarked/indexed URL 301s to the new one instead of 404ing. Always a
-- permanent redirect by design — no status_code column needed.
-- Safe to run against the existing echos_dev DB (idempotent).

USE echos_dev;

CREATE TABLE IF NOT EXISTS redirects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    from_path VARCHAR(255) NOT NULL,
    to_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_redirects_from_path (from_path)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
