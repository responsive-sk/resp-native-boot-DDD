-- APP DATABASE: Images, Audit Logs, Sessions, etc.
CREATE TABLE IF NOT EXISTS images (
    id TEXT PRIMARY KEY,
    filename TEXT NOT NULL,
    original_name TEXT NOT NULL,
    mime_type TEXT NOT NULL,
    size INTEGER NOT NULL,
    width INTEGER,
    height INTEGER,
    alt_text TEXT,
    caption TEXT,
    cloudinary_public_id TEXT UNIQUE,
    cloudinary_url TEXT NOT NULL,
    cloudinary_secure_url TEXT NOT NULL,
    format TEXT,
    created_by TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE IF NOT EXISTS article_images (
    article_id TEXT NOT NULL,
    image_id TEXT NOT NULL,
    is_featured INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY (article_id, image_id),
    FOREIGN KEY (image_id) REFERENCES images(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS audit_logs (
    id TEXT PRIMARY KEY,
    user_id TEXT NULL,
    event_type TEXT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    metadata TEXT, -- JSON
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
);

-- Indexes
CREATE INDEX idx_images_created_by ON images(created_by);
CREATE INDEX idx_images_cloudinary_public_id ON images(cloudinary_public_id);
CREATE INDEX idx_images_created_at ON images(created_at);
CREATE INDEX idx_article_images_article_id ON article_images(article_id);
CREATE INDEX idx_article_images_image_id ON article_images(image_id);
CREATE INDEX idx_audit_logs_user_id ON audit_logs(user_id);
CREATE INDEX idx_audit_logs_event_type ON audit_logs(event_type);
CREATE INDEX idx_audit_logs_created_at ON audit_logs(created_at);

-- Triggers for updated_at
CREATE TRIGGER IF NOT EXISTS update_images_timestamp 
AFTER UPDATE ON images 
FOR EACH ROW
BEGIN
    UPDATE images SET updated_at = CURRENT_TIMESTAMP WHERE id = OLD.id;
END;
