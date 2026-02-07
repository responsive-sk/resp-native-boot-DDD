-- migrations/articles/002_add_fts5.sql
-- Enable FTS5 for full-text search

-- Create FTS5 virtual table without external content
CREATE VIRTUAL TABLE IF NOT EXISTS articles_fts USING fts5(
    article_id UNINDEXED,
    title,
    content,
    excerpt,
    author_name,
    tokenize='porter'
);

-- Populate existing articles with FTS data
INSERT INTO articles_fts(article_id, title, content, excerpt, author_name)
SELECT a.id, a.title, a.content, a.excerpt, auth.username
FROM articles a
JOIN authors auth ON a.author_id = auth.id;

-- Triggers for synchronization
CREATE TRIGGER IF NOT EXISTS articles_ai AFTER INSERT ON articles
BEGIN
    INSERT INTO articles_fts(article_id, title, content, excerpt, author_name)
    VALUES (new.id, new.title, new.content, new.excerpt, 
            (SELECT username FROM authors WHERE authors.id = new.author_id));
END;

CREATE TRIGGER IF NOT EXISTS articles_ad AFTER DELETE ON articles
BEGIN
    DELETE FROM articles_fts WHERE article_id = old.id;
END;

CREATE TRIGGER IF NOT EXISTS articles_au AFTER UPDATE ON articles
BEGIN
    DELETE FROM articles_fts WHERE article_id = old.id;
    INSERT INTO articles_fts(article_id, title, content, excerpt, author_name)
    VALUES (new.id, new.title, new.content, new.excerpt, 
            (SELECT username FROM authors WHERE authors.id = new.author_id));
END;

-- Create indexes for better performance (FTS tables have built-in indexes)
-- Note: FTS5 virtual tables automatically create their own indexes
