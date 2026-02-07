-- scripts/migrations/app/001_init_app_schema.sql
-- Consolidated initial schema for the 'app' database (including shared_images)

-- TABLE: shared_images (from 001_shared_media.sql)
CREATE TABLE IF NOT EXISTS shared_images (
    id TEXT PRIMARY KEY,
    filename TEXT NOT NULL,
    original_name TEXT NOT NULL,
    mime_type TEXT NOT NULL,
    size INTEGER NOT NULL,
    width INTEGER,
    height INTEGER,
    alt_text TEXT,
    caption TEXT,
    storage_path TEXT NOT NULL,
    cloudinary_public_id TEXT UNIQUE,
    cloudinary_url TEXT NOT NULL,
    cloudinary_secure_url TEXT NOT NULL,
    format TEXT, -- Added from original images table
    created_by TEXT NOT NULL, -- Added from original images table
    variants TEXT, -- JSON
    contexts TEXT, -- JSON array
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- TABLE: article_images
CREATE TABLE IF NOT EXISTS article_images (
    article_id TEXT NOT NULL,
    image_id TEXT NOT NULL,
    is_featured INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY (article_id, image_id),
    FOREIGN KEY (image_id) REFERENCES shared_images(id) ON DELETE CASCADE
);

-- TABLE: audit_logs
CREATE TABLE IF NOT EXISTS audit_logs (
    id TEXT PRIMARY KEY,
    user_id TEXT NULL,
    event_type TEXT NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    metadata TEXT NULL, -- JSON
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
);

-- TABLE: system_events
CREATE TABLE IF NOT EXISTS system_events (
    id TEXT PRIMARY KEY,
    event_type TEXT NOT NULL,
    description TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
);

-- INSERT: system_events initial data
INSERT OR IGNORE INTO system_events (id, event_type, description) VALUES
    ('audit-001', 'login_success', 'User login successful'),
    ('audit-002', 'login_failed', 'User login failed'),
    ('audit-003', 'logout', 'User logout'),
    ('audit-004', 'registration', 'User registration'),
    ('audit-005', 'authentication_required', 'Authentication required'),
    ('audit-006', 'authorization_denied', 'Authorization denied'),
    ('audit-007', 'article_created', 'Article created'),
    ('audit-008', 'article_updated', 'Article updated'),
    ('audit-009', 'article_deleted', 'Article deleted'),
    ('audit-010', 'image_uploaded', 'Image uploaded'),
    ('audit-011', 'image_deleted', 'Image deleted'),
    ('audit-012', 'form_submitted', 'Form submitted');

-- INDEXES
CREATE INDEX IF NOT EXISTS idx_shared_images_cloudinary_id ON shared_images(cloudinary_public_id);
CREATE INDEX IF NOT EXISTS idx_shared_images_contexts ON shared_images(contexts);
CREATE INDEX IF NOT EXISTS idx_shared_images_created_at ON shared_images(created_at);
CREATE INDEX IF NOT EXISTS idx_shared_images_mime_type ON shared_images(mime_type);
CREATE INDEX IF NOT EXISTS idx_shared_images_created_by ON shared_images(created_by); -- For consistency

CREATE INDEX IF NOT EXISTS idx_article_images_article_id ON article_images(article_id);
CREATE INDEX IF NOT EXISTS idx_article_images_image_id ON article_images(image_id);

CREATE INDEX IF NOT EXISTS idx_audit_logs_user_id ON audit_logs(user_id);
CREATE INDEX IF NOT EXISTS idx_audit_logs_event_type ON audit_logs(event_type);
CREATE INDEX IF NOT EXISTS idx_audit_logs_created_at ON audit_logs(created_at);
CREATE INDEX IF NOT EXISTS idx_audit_logs_user_event ON audit_logs(user_id, event_type);
CREATE INDEX IF NOT EXISTS idx_audit_logs_date_event ON audit_logs(created_at, event_type);

-- VIEWS (from 001_shared_media.sql)
DROP VIEW IF EXISTS orphaned_images;
CREATE VIEW IF NOT EXISTS orphaned_images AS
SELECT
    id,
    filename, -- Added to match shared_images columns
    original_name, -- Added to match shared_images columns
    mime_type, -- Added to match shared_images columns
    size, -- Added to match shared_images columns
    width, -- Added to match shared_images columns
    height, -- Added to match shared_images columns
    alt_text, -- Added to match shared_images columns
    caption, -- Added to match shared_images columns
    storage_path, -- Added to match shared_images columns
    cloudinary_public_id, -- Added to match shared_images columns
    cloudinary_url, -- Added to match shared_images columns
    cloudinary_secure_url, -- Added to match shared_images columns
    format, -- Added to match shared_images columns
    created_by, -- Added to match shared_images columns
    variants, -- Added to match shared_images columns
    contexts, -- Added to match shared_images columns
    created_at,
    updated_at
FROM shared_images
WHERE (contexts IS NULL OR contexts = '[]')
AND created_at < datetime('now', '-24 hours');

DROP VIEW IF EXISTS image_statistics;
CREATE VIEW IF NOT EXISTS image_statistics AS
SELECT
    COUNT(*) as total_images,
    COUNT(CASE WHEN contexts IS NOT NULL AND contexts != '[]' THEN 1 END) as used_images,
    COUNT(CASE WHEN contexts IS NULL OR contexts = '[]' THEN 1 END) as orphaned_images,
    SUM(size) as total_size,
    AVG(size) as average_size,
    COUNT(DISTINCT mime_type) as unique_mime_types
FROM shared_images;

-- VIEWS (from original 001_init_app_schema.sql)
DROP VIEW IF EXISTS vw_recent_audits;
CREATE VIEW IF NOT EXISTS vw_recent_audits AS
SELECT
    id,
    event_type,
    user_id,
    ip_address,
    user_agent,
    created_at,
    json_extract(metadata, '$.path') as request_path,
    json_extract(metadata, '$.method') as request_method,
    json_extract(metadata, '$.status_code') as status_code
FROM audit_logs
ORDER BY created_at DESC
LIMIT 1000;

-- TRIGGERS (from 001_shared_media.sql)
CREATE TRIGGER IF NOT EXISTS shared_images_updated_at
    AFTER UPDATE ON shared_images
    FOR EACH ROW
BEGIN
    UPDATE shared_images SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
END;