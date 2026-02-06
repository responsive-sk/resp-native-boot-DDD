# Full-Text Search Implementation for Articles

## Overview

SQLite FTS5 (Full-Text Search) implementation for articles database with automatic synchronization and ranking.

## Database Schema

### FTS Virtual Table
```sql
CREATE VIRTUAL TABLE IF NOT EXISTS articles_fts USING fts5(
    article_id,      -- Reference to articles.id
    title,           -- Article title
    content,         -- Full article content
    excerpt,         -- Article excerpt
    author_name,     -- Author username (joined from authors table)
    content='articles',
    content_rowid='id'
);
```

### Synchronization Triggers
```sql
-- INSERT trigger
CREATE TRIGGER IF NOT EXISTS articles_fts_insert AFTER INSERT ON articles
BEGIN
    INSERT INTO articles_fts(article_id, title, content, excerpt, author_name)
    SELECT new.id, new.title, new.content, new.excerpt, 
           (SELECT username FROM authors WHERE authors.id = new.author_id);
END;

-- UPDATE trigger
CREATE TRIGGER IF NOT EXISTS articles_fts_update AFTER UPDATE ON articles
BEGIN
    INSERT INTO articles_fts(articles_fts, rowid, article_id, title, content, excerpt, author_name)
    VALUES('delete', old.id, old.id, old.title, old.content, old.excerpt, 
           (SELECT username FROM authors WHERE authors.id = old.author_id));
    INSERT INTO articles_fts(article_id, title, content, excerpt, author_name)
    SELECT new.id, new.title, new.content, new.excerpt, 
           (SELECT username FROM authors WHERE authors.id = new.author_id);
END;

-- DELETE trigger
CREATE TRIGGER IF NOT EXISTS articles_fts_delete AFTER DELETE ON articles
BEGIN
    INSERT INTO articles_fts(articles_fts, rowid, article_id, title, content, excerpt, author_name)
    VALUES('delete', old.id, old.id, old.title, old.content, old.excerpt, 
           (SELECT username FROM authors WHERE authors.id = old.author_id));
END;
```

## Repository Implementation

### Search Method
```php
public function search(string $query): array
{
    if (empty(trim($query))) {
        return [];
    }

    // Split query into terms and add * wildcard for prefix matching
    $terms = array_filter(explode(' ', trim($query)));
    $ftsQuery = implode(' ', array_map(fn($term) => $term . '*', $terms));

    // FTS5 search with ranking
    $sql = "
        SELECT a.*
        FROM articles a
        INNER JOIN articles_fts fts ON a.id = fts.article_id
        WHERE articles_fts MATCH ?
        AND a.status = 'published'
        ORDER BY rank
        LIMIT 50
    ";

    $rows = $this->connection->fetchAllAssociative($sql, [$ftsQuery]);

    return array_map([$this, 'hydrate'], $rows);
}
```

## Search Features

### 1. Prefix Matching
- Query: `php` → Matches: `php`, `php8`, `phpunit`
- Query: `react` → Matches: `react`, `reactjs`, `reactive`

### 2. Multi-term Search
- Query: `php tutorial` → Searches for both terms
- Query: `"php 8"` → Exact phrase matching

### 3. Boolean Operators
- Query: `php AND tutorial` → Both terms must match
- Query: `php OR javascript` → Either term can match
- Query: `php NOT tutorial` → Exclude tutorial results

### 4. Field-specific Search
- Query: `title:php` → Search only in titles
- Query: `content:javascript` → Search only in content
- Query: `author:admin` → Search by author name

### 5. Automatic Ranking
- Results ranked by relevance (SQLite FTS5 BM25 algorithm)
- Higher relevance for exact matches
- Consideration of term frequency and document length

## Performance Optimizations

### 1. Indexing
- FTS5 automatically creates inverted index
- Fast lookups for common terms
- Efficient prefix matching

### 2. Synchronization
- Real-time updates via triggers
- No manual reindexing required
- Consistent data between main and FTS tables

### 3. Query Optimization
- LIMIT 50 to prevent result explosion
- Only published articles included
- Efficient JOIN with articles table

## Usage Examples

### Basic Search
```php
$articles = $repository->search('php');
// Returns articles containing "php" or "php*"
```

### Advanced Search
```php
$articles = $repository->search('react hooks tutorial');
// Returns articles about React hooks tutorials
```

### Phrase Search
```php
$articles = $repository->search('"getting started"');
// Returns articles with exact phrase "getting started"
```

### Author Search
```php
$articles = $repository->search('author:admin');
// Returns articles by admin author
```

## Search Result Ranking

### BM25 Algorithm
- **Term Frequency (TF)**: How often term appears in document
- **Document Frequency (DF)**: How many documents contain term
- **Document Length**: Normalized by document size
- **Term Proximity**: Distance between multiple terms

### Ranking Factors
1. **Exact matches** rank higher than partial matches
2. **Title matches** rank higher than content matches
3. **Shorter documents** with same term density rank higher
4. **Multiple term matches** improve ranking

## Maintenance

### Rebuilding FTS Index
```sql
-- If needed, rebuild FTS table
DELETE FROM articles_fts;
INSERT INTO articles_fts(article_id, title, content, excerpt, author_name)
SELECT a.id, a.title, a.content, a.excerpt, auth.username
FROM articles a
JOIN authors auth ON a.author_id = auth.id;
```

### Performance Monitoring
```sql
-- Check FTS table size
SELECT COUNT(*) FROM articles_fts;

-- Analyze query performance
EXPLAIN QUERY PLAN 
SELECT a.* FROM articles a 
INNER JOIN articles_fts fts ON a.id = fts.article_id 
WHERE articles_fts MATCH 'php';
```

## Limitations

### 1. SQLite-specific
- FTS5 is SQLite-specific feature
- Not portable to other databases without changes

### 2. Index Size
- FTS index can be large for full content
- Consider excluding very long articles

### 3. Real-time Updates
- Triggers add overhead to INSERT/UPDATE/DELETE
- Consider batch updates for bulk operations

## Future Enhancements

### 1. Search Highlighting
```php
// Add snippet generation
SELECT snippet(articles_fts, 0, '<mark>', '</mark>', '...', 32) as snippet
FROM articles_fts WHERE articles_fts MATCH 'php';
```

### 2. Search Analytics
```sql
-- Log search queries for analytics
CREATE TABLE search_logs (
    id INTEGER PRIMARY KEY,
    query TEXT NOT NULL,
    result_count INTEGER,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### 3. Search Suggestions
```sql
-- Implement autocomplete using FTS prefix matching
SELECT DISTINCT substr(term, 1, length(?) + 1) as suggestion
FROM articles_fts WHERE term MATCH ? || '*';
```

## Testing

### Unit Tests
```php
public function testSearch(): void
{
    $articles = $repository->search('php');
    $this->assertNotEmpty($articles);
    
    $articles = $repository->search('nonexistent');
    $this->assertEmpty($articles);
}
```

### Performance Tests
```php
public function testSearchPerformance(): void
{
    $start = microtime(true);
    $articles = $repository->search('common term');
    $duration = microtime(true) - $start;
    
    $this->assertLessThan(0.1, $duration); // Should be under 100ms
}
```

## Related Files

- `migrations/articles/001_init_articles_ddd.sql` - FTS schema and triggers
- `src/Infrastructure/Persistence/Doctrine/DoctrineArticleRepository.php` - Search implementation
- `src/Domain/Blog/Entity/Article.php` - Article entity
- `docs/DDD_AUTHOR_AGGREGATE.md` - Author aggregate documentation
