-- Beta initialization migration for articles.sqlite
-- This migration creates the complete database schema for the blog system

-- Drop existing tables if they exist (for clean init)
DROP TABLE IF EXISTS articles_fts;
DROP TABLE IF EXISTS articles;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS tags;
DROP TABLE IF EXISTS article_tags;

-- Create categories table
CREATE TABLE categories (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Create tags table
CREATE TABLE tags (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    slug VARCHAR(50) NOT NULL UNIQUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Create article_tags junction table (many-to-many relationship)
CREATE TABLE article_tags (
    article_id INTEGER NOT NULL,
    tag_id VARCHAR(36) NOT NULL,
    PRIMARY KEY (article_id, tag_id),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- Create articles table
CREATE TABLE articles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NULL,
    content TEXT NOT NULL,
    user_id BLOB NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'draft',
    category_id VARCHAR(36) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Create indexes for better performance
CREATE INDEX idx_categories_slug ON categories(slug);
CREATE INDEX idx_categories_name ON categories(name);
CREATE INDEX idx_tags_slug ON tags(slug);
CREATE INDEX idx_tags_name ON tags(name);
CREATE INDEX idx_articles_slug ON articles(slug);
CREATE INDEX idx_articles_status ON articles(status);
CREATE INDEX idx_articles_created_at ON articles(created_at DESC);
CREATE INDEX idx_articles_category_id ON articles(category_id);
CREATE INDEX idx_article_tags_article_id ON article_tags(article_id);
CREATE INDEX idx_article_tags_tag_id ON article_tags(tag_id);

-- Create FTS5 virtual table for full-text search on articles
CREATE VIRTUAL TABLE articles_fts USING fts5(
    title, 
    content,
    content='articles',
    content_rowid='id'
);

-- Triggers to keep FTS in sync with articles table
CREATE TRIGGER articles_fts_insert AFTER INSERT ON articles BEGIN
    INSERT INTO articles_fts(rowid, title, content) VALUES (new.id, new.title, new.content);
END;

CREATE TRIGGER articles_fts_delete AFTER DELETE ON articles BEGIN
    INSERT INTO articles_fts(articles_fts, rowid, title, content) VALUES('delete', old.id, old.title, old.content);
END;

CREATE TRIGGER articles_fts_update AFTER UPDATE ON articles BEGIN
    INSERT INTO articles_fts(articles_fts, rowid, title, content) VALUES('delete', old.id, old.title, old.content);
    INSERT INTO articles_fts(rowid, title, content) VALUES (new.id, new.title, new.content);
END;

-- Insert default categories
INSERT INTO categories (id, name, slug, description, created_at, updated_at) VALUES
    ('00000000-0000-0000-0000-000000000001', 'Všeobecne', 'vseobecne', 'Všeobecné články a novinky', datetime('now'), datetime('now')),
    ('00000000-0000-0000-0000-000000000002', 'Technológie', 'technologie', 'Články o technológiách, programovaní a IT', datetime('now'), datetime('now')),
    ('00000000-0000-0000-0000-000000000003', 'Spoločnosť', 'spolocnost', 'Spoločenské témy a udalosti', datetime('now'), datetime('now')),
    ('00000000-0000-0000-0000-000000000004', 'Šport', 'sport', 'Športové správy a udalosti', datetime('now'), datetime('now')),
    ('00000000-0000-0000-0000-000000000005', 'Kultúra', 'kultura', 'Kultúrne podujatia a umenie', datetime('now'), datetime('now'));

-- Insert default tags
INSERT INTO tags (id, name, slug, created_at) VALUES
    ('00000000-0000-0000-0000-000000000001', 'PHP', 'php', datetime('now')),
    ('00000000-0000-0000-0000-000000000002', 'JavaScript', 'javascript', datetime('now')),
    ('00000000-0000-0000-0000-000000000003', 'Web', 'web', datetime('now')),
    ('00000000-0000-0000-0000-000000000004', 'Databázy', 'databazy', datetime('now')),
    ('00000000-0000-0000-0000-000000000005', 'UX/UI', 'ux-ui', datetime('now')),
    ('00000000-0000-0000-0000-000000000006', 'DevOps', 'devops', datetime('now')),
    ('00000000-0000-0000-0000-000000000007', 'AI', 'ai', datetime('now')),
    ('00000000-0000-0000-0000-000000000008', 'Mobile', 'mobile', datetime('now')),
    ('00000000-0000-0000-0000-000000000009', 'Security', 'security', datetime('now')),
    ('00000000-0000-0000-0000-000000000010', 'Tutorial', 'tutorial', datetime('now'));
