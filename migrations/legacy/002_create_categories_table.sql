-- Create categories table in articles.sqlite database
CREATE TABLE IF NOT EXISTS categories (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Create index for faster lookups
CREATE INDEX IF NOT EXISTS idx_categories_slug ON categories(slug);
CREATE INDEX IF NOT EXISTS idx_categories_name ON categories(name);

-- Insert some default categories
INSERT OR IGNORE INTO categories (id, name, slug, description, created_at, updated_at) VALUES
    ('00000000-0000-0000-0000-000000000001', 'Všeobecne', 'vseobecne', 'Všeobecné články a novinky', datetime('now'), datetime('now')),
    ('00000000-0000-0000-0000-000000000002', 'Technológie', 'technologie', 'Články o technológiách, programovaní a IT', datetime('now'), datetime('now')),
    ('00000000-0000-0000-0000-000000000003', 'Spoločnosť', 'spolocnost', 'Spoločenské témy a udalosti', datetime('now'), datetime('now'));
