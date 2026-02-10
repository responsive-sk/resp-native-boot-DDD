-- Migration: Add performance indexes for frequently queried fields
-- These indexes optimize the most common query patterns in the application

-- Composite index for published articles ordered by date (findPublished, getRecentArticles)
-- Covers: WHERE status = 'published' ORDER BY created_at DESC
CREATE INDEX IF NOT EXISTS idx_articles_status_created_at ON articles(status, created_at DESC);

-- Index for category JOINs (getByCategory, hydrateWithPreloadedData)
CREATE INDEX IF NOT EXISTS idx_articles_category_id ON articles(category_id);

-- Composite index for category listings with status filter
-- Covers: WHERE category_id = ? AND status = 'published' ORDER BY created_at DESC
CREATE INDEX IF NOT EXISTS idx_articles_category_status_created ON articles(category_id, status, created_at DESC);

-- Index for FTS5 search results ordering
-- Covers: FTS5 MATCH + ORDER BY rank with status filter
CREATE INDEX IF NOT EXISTS idx_articles_status_rank ON articles(status);
