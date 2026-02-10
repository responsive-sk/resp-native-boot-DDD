-- Migration: Add composite index for optimized article queries
-- This index significantly improves performance of findPublished() queries

CREATE INDEX IF NOT EXISTS idx_articles_status_created_at ON articles(status, created_at DESC);

-- Add category_id index for JOIN queries
CREATE INDEX IF NOT EXISTS idx_articles_category_id ON articles(category_id);
