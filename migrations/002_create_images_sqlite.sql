-- Unified SQLite migration for images and related tables
-- This migration creates all necessary tables for the image management system using SQLite

-- Images table - stores main image information
CREATE TABLE IF NOT EXISTS images (
    id TEXT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    path VARCHAR(500) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    size INTEGER NOT NULL,
    width INTEGER NOT NULL,
    height INTEGER NOT NULL,
    title VARCHAR(255),
    alt_text TEXT,
    caption TEXT,
    description TEXT,
    is_featured BOOLEAN DEFAULT FALSE,
    tags TEXT DEFAULT '[]', -- JSON as TEXT for SQLite
    exif_data TEXT DEFAULT '{}', -- JSON as TEXT for SQLite
    credit VARCHAR(255),
    uploaded_by TEXT REFERENCES users(id) ON DELETE SET NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Image variants (thumbnails, medium, large, etc.)
CREATE TABLE IF NOT EXISTS image_variants (
    id TEXT PRIMARY KEY,
    image_id TEXT NOT NULL REFERENCES images(id) ON DELETE CASCADE,
    variant_name VARCHAR(50) NOT NULL, -- 'thumbnail', 'medium', 'large', 'original'
    width INTEGER NOT NULL,
    height INTEGER NOT NULL,
    path VARCHAR(500) NOT NULL,
    size INTEGER NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(image_id, variant_name)
);

-- Article images relationship - links images to articles
CREATE TABLE IF NOT EXISTS article_images (
    article_id TEXT NOT NULL REFERENCES articles(id) ON DELETE CASCADE,
    image_id TEXT NOT NULL REFERENCES images(id) ON DELETE CASCADE,
    is_featured BOOLEAN DEFAULT FALSE,
    caption TEXT,
    alt_text TEXT,
    sort_order INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (article_id, image_id)
);

-- User avatars - links users to their avatar images
CREATE TABLE IF NOT EXISTS user_avatars (
    user_id TEXT PRIMARY KEY REFERENCES users(id) ON DELETE CASCADE,
    image_id TEXT NOT NULL REFERENCES images(id) ON DELETE CASCADE,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Category cover images
CREATE TABLE IF NOT EXISTS category_images (
    category_id TEXT PRIMARY KEY REFERENCES categories(id) ON DELETE CASCADE,
    image_id TEXT NOT NULL REFERENCES images(id) ON DELETE CASCADE,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- System images (logos, favicons, icons)
CREATE TABLE IF NOT EXISTS system_images (
    id TEXT PRIMARY KEY,
    image_type VARCHAR(50) NOT NULL, -- 'logo', 'favicon', 'icon'
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image_id TEXT NOT NULL REFERENCES images(id) ON DELETE CASCADE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(image_type, name)
);

-- Image processing queue - for background processing
CREATE TABLE IF NOT EXISTS image_processing_queue (
    id TEXT PRIMARY KEY,
    image_id TEXT NOT NULL REFERENCES images(id) ON DELETE CASCADE,
    operation VARCHAR(50) NOT NULL, -- 'resize', 'optimize', 'watermark'
    parameters TEXT DEFAULT '{}', -- JSON as TEXT for SQLite
    status VARCHAR(20) DEFAULT 'pending', -- 'pending', 'processing', 'completed', 'failed'
    error_message TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    processed_at DATETIME
);

-- Indexes for better performance
CREATE INDEX IF NOT EXISTS idx_images_uploaded_by ON images(uploaded_by);
CREATE INDEX IF NOT EXISTS idx_images_mime_type ON images(mime_type);
CREATE INDEX IF NOT EXISTS idx_images_created_at ON images(created_at);
CREATE INDEX IF NOT EXISTS idx_image_variants_image_id ON image_variants(image_id);
CREATE INDEX IF NOT EXISTS idx_article_images_article_id ON article_images(article_id);
CREATE INDEX IF NOT EXISTS idx_article_images_image_id ON article_images(image_id);
CREATE INDEX IF NOT EXISTS idx_article_images_featured ON article_images(is_featured);
CREATE INDEX IF NOT EXISTS idx_image_processing_queue_status ON image_processing_queue(status);
CREATE INDEX IF NOT EXISTS idx_image_processing_queue_created_at ON image_processing_queue(created_at);

-- Insert default system images placeholder
INSERT OR IGNORE INTO system_images (image_type, name, description) VALUES
('logo', 'site_logo', 'Main site logo'),
('favicon', 'site_favicon', 'Site favicon'),
('icon', 'default_avatar', 'Default user avatar icon');

-- Create triggers for updated_at timestamp and UUID generation
CREATE TRIGGER IF NOT EXISTS generate_images_id BEFORE INSERT ON images
    WHEN NEW.id IS NULL
    BEGIN
        UPDATE images SET id = (lower(hex(randomblob(4))) || '-' || lower(hex(randomblob(2))) || '-' || lower(hex(randomblob(2))) || '-' || lower(hex(randomblob(2))) || '-' || lower(hex(randomblob(6)))) WHERE rowid = NEW.rowid;
    END;

CREATE TRIGGER IF NOT EXISTS generate_image_variants_id BEFORE INSERT ON image_variants
    WHEN NEW.id IS NULL
    BEGIN
        UPDATE image_variants SET id = (lower(hex(randomblob(4))) || '-' || lower(hex(randomblob(2))) || '-' || lower(hex(randomblob(2))) || '-' || lower(hex(randomblob(2))) || '-' || lower(hex(randomblob(6)))) WHERE rowid = NEW.rowid;
    END;

CREATE TRIGGER IF NOT EXISTS generate_system_images_id BEFORE INSERT ON system_images
    WHEN NEW.id IS NULL
    BEGIN
        UPDATE system_images SET id = (lower(hex(randomblob(4))) || '-' || lower(hex(randomblob(2))) || '-' || lower(hex(randomblob(2))) || '-' || lower(hex(randomblob(2))) || '-' || lower(hex(randomblob(6)))) WHERE rowid = NEW.rowid;
    END;

CREATE TRIGGER IF NOT EXISTS generate_image_processing_queue_id BEFORE INSERT ON image_processing_queue
    WHEN NEW.id IS NULL
    BEGIN
        UPDATE image_processing_queue SET id = (lower(hex(randomblob(4))) || '-' || lower(hex(randomblob(2))) || '-' || lower(hex(randomblob(2))) || '-' || lower(hex(randomblob(2))) || '-' || lower(hex(randomblob(6)))) WHERE rowid = NEW.rowid;
    END;

CREATE TRIGGER IF NOT EXISTS update_images_updated_at BEFORE UPDATE ON images
    BEGIN
        UPDATE images SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
    END;

CREATE TRIGGER IF NOT EXISTS update_system_images_updated_at BEFORE UPDATE ON system_images
    BEGIN
        UPDATE system_images SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
    END;
