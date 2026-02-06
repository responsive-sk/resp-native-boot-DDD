# Database Migrations Guide

## Overview

Tento dokument popisuje všetky databázové zmeny a migrácie v rámci DDD refaktorizácie blog aplikácie.

## DDD Architektúra Databáz

### Bounded Contexts a Databázy

Aplikácia používa viacero databáz podľa bounded contexts:

```
data/
├── app.db          # Application context (images, audit logs, sessions)
├── articles.db     # Articles context (articles, categories, tags, authors)
├── users.db        # Users context (používatelia, autentifikácia)
└── forms.db        # Forms context (formuláre, submissions)
```

### DDD Principály

1. **Bounded Context Integrity** - každý context má vlastnú databázu
2. **No Cross-Database Joins** - žiadne JOINy medzi databázami
3. **Data Duplication** - potrebné informácie sú duplikované v kontextoch
4. **Event-Driven Sync** - synchronizácia cez domain events

## Migrácie

### Štruktúra Migrácií

```
migrations/
├── app/
│   └── 001_init_app_ddd.sql
├── articles/
│   └── 001_init_articles_ddd.sql
├── users/
│   └── 001_init_users_ddd.sql
├── forms/
│   └── 001_init_forms_ddd.sql
└── run_migrations.php
```

### Spustenie Migrácií

```bash
# Kompletná inštalácia
php scripts/install.php

# Iba migrácie
php migrations/run_migrations.php

# Force re-run všetkých migrácií
php migrations/run_migrations.php --force
```

## Detailné Zmeny

### 1. Articles Database (articles.db)

#### Author Aggregate Namiesto User Reference

**Pred refaktorizáciou:**
```sql
-- articles mala priamy odkaz na users
author_id TEXT NOT NULL,  -- odkaz na users.db
```

**Po refaktorizácii:**
```sql
-- Vlastný Author aggregate v articles context
CREATE TABLE authors (
    id TEXT PRIMARY KEY,
    username TEXT UNIQUE NOT NULL,
    email TEXT UNIQUE NOT NULL,
    role TEXT NOT NULL DEFAULT 'author',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE articles (
    -- ...
    author_id TEXT NOT NULL,
    FOREIGN KEY (author_id) REFERENCES authors(id)
);
```

**Výhody:**
- ✅ Žiadne cross-database joins
- ✅ Vlastný bounded context integrity
- ✅ Lepšia performance
- ✅ Nezávislá škálovateľnosť

#### Full-Text Search (FTS5)

**Nová FTS tabuľka:**
```sql
CREATE VIRTUAL TABLE IF NOT EXISTS articles_fts USING fts5(
    article_id UNINDEXED,
    title,
    content,
    excerpt,
    author_name
);
```

**Automatické triggery:**
```sql
-- INSERT trigger
CREATE TRIGGER articles_fts_insert AFTER INSERT ON articles
BEGIN
    INSERT INTO articles_fts(article_id, title, content, excerpt, author_name)
    SELECT new.id, new.title, new.content, new.excerpt, 
           (SELECT username FROM authors WHERE authors.id = new.author_id);
END;
```

#### Updated Categories a Tags

**Pridané `updated_at` stĺpce:**
```sql
CREATE TABLE categories (
    -- ...
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TRIGGER update_categories_timestamp 
AFTER UPDATE ON categories 
FOR EACH ROW
BEGIN
    UPDATE categories SET updated_at = CURRENT_TIMESTAMP WHERE id = OLD.id;
END;
```

### 2. Users Database (users.db)

#### Zachovaná Pôvodná Štruktúra

```sql
CREATE TABLE users (
    id TEXT PRIMARY KEY,
    email TEXT UNIQUE NOT NULL,
    username TEXT UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT 'user',
    is_active INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    last_login_at DATETIME NULL
);
```

**Default Admin Account:**
```sql
INSERT OR IGNORE INTO users (id, email, username, password_hash, role) 
VALUES (
    '00000000-0000-0000-0000-000000000001',
    'admin@example.com',
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin'
);
```

### 3. App Database (app.db)

#### Images a Audit Logs

```sql
CREATE TABLE images (
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

CREATE TABLE audit_logs (
    id TEXT PRIMARY KEY,
    user_id TEXT NULL,
    event_type TEXT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    metadata TEXT, -- JSON
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
);
```

### 4. Forms Database (forms.db)

#### Formuláre a Submissions

```sql
CREATE TABLE forms (
    id TEXT PRIMARY KEY,
    name TEXT NOT NULL,
    slug TEXT UNIQUE NOT NULL,
    description TEXT,
    structure TEXT NOT NULL, -- JSON
    is_active INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE form_submissions (
    id TEXT PRIMARY KEY,
    form_id TEXT NOT NULL,
    submitted_data TEXT NOT NULL, -- JSON
    submitted_by TEXT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY (form_id) REFERENCES forms(id) ON DELETE CASCADE
);
```

## Sample Dáta

### Articles Sample Data

**10 článkov s rôznymi autormi:**
```sql
-- PHP 8 článok
('30000000-0000-0000-0000-000000000001', 'Getting Started with PHP 8', 'getting-started-with-php-8', '...', '...', '40000000-0000-0000-0000-000000000001', 'published', '2024-01-15 10:00:00');

-- JavaScript článok  
('30000000-0000-0000-0000-000000000002', 'Modern JavaScript ES6+ Features', 'modern-javascript-es6-features', '...', '...', '40000000-0000-0000-0000-000000000001', 'published', '2024-01-20 14:30:00');
```

**4 kategórie:**
```sql
('10000000-0000-0000-0000-000000000001', 'Technology', 'technology', 'Latest tech news and innovations'),
('10000000-0000-0000-0000-000000000002', 'Programming', 'programming', 'Programming tutorials and best practices'),
('10000000-0000-0000-0000-000000000003', 'Web Development', 'web-development', 'Web dev tips and frameworks'),
('10000000-0000-0000-0000-000000000004', 'Design', 'design', 'UI/UX design principles and trends');
```

**10 tagov:**
```sql
('20000000-0000-0000-0000-000000000001', 'PHP', 'php'),
('20000000-0000-0000-0000-000000000002', 'JavaScript', 'javascript'),
('20000000-0000-0000-0000-000000000003', 'React', 'react'),
-- ...
```

**3 autori v articles context:**
```sql
('40000000-0000-0000-0000-000000000001', 'admin', 'admin@example.com', 'admin'),
('40000000-0000-0000-0000-000000000002', 'editor', 'editor@example.com', 'editor'),
('40000000-0000-0000-0000-000000000003', 'author', 'author@example.com', 'author');
```

## Domain Events

### ArticlePublishedEvent

```php
final readonly class ArticlePublishedEvent implements DomainEvent
{
    private DateTimeImmutable $occurredOn;

    public function __construct(private ArticleId $articleId) {
        $this->occurredOn = new DateTimeImmutable();
    }

    public function articleId(): ArticleId { return $this->articleId; }
    public function occurredOn(): DateTimeImmutable { return $this->occurredOn; }
    public function eventName(): string { return 'article.published'; }
}
```

### Budúce Event-Driven Sync

**Plánované events pre synchronizáciu:**
- `UserCreatedEvent` → vytvorí Author v articles context
- `UserUpdatedEvent` → aktualizuje Author v articles context
- `UserDeletedEvent` → archivuje Author v articles context

## Performance Optimalizácie

### Indexy

**Articles databáza:**
```sql
-- Primárne indexy
CREATE INDEX idx_articles_slug ON articles(slug);
CREATE INDEX idx_articles_status ON articles(status);
CREATE INDEX idx_articles_author_id ON articles(author_id);
CREATE INDEX idx_articles_published_at ON articles(published_at);

-- FTS index (automatický)
-- articles_fts virtual table má vlastný inverted index
```

**App databáza:**
```sql
CREATE INDEX idx_images_created_by ON images(created_by);
CREATE INDEX idx_images_cloudinary_public_id ON images(cloudinary_public_id);
CREATE INDEX idx_audit_logs_user_id ON audit_logs(user_id);
CREATE INDEX idx_audit_logs_event_type ON audit_logs(event_type);
```

### Triggery

**Automatické timestamp updates:**
```sql
-- Articles
CREATE TRIGGER update_articles_timestamp AFTER UPDATE ON articles
BEGIN
    UPDATE articles SET updated_at = CURRENT_TIMESTAMP WHERE id = OLD.id;
END;

-- Categories
CREATE TRIGGER update_categories_timestamp AFTER UPDATE ON categories
BEGIN
    UPDATE categories SET updated_at = CURRENT_TIMESTAMP WHERE id = OLD.id;
END;
```

## Bezpečnosť

### Password Hashing

**BCrypt hash pre admin účet:**
```php
// Hash pre 'admin123'
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
```

### Session Security

**Fingerprinting:**
```env
SESSION_FINGERPRINT_SALT=8d7b128ab98a11c5c29c87b9bf37dc96eb94a5f920d864b303dfd87eeda43552
```

## Testovanie

### Unit Test Data

**Testovacie databázy:**
```bash
# Vytvorenie test databáz
rm -f data/*.db
php migrations/run_migrations.php

# Overenie dát
sqlite3 data/articles.db "SELECT COUNT(*) FROM articles;"
sqlite3 data/articles.db "SELECT COUNT(*) FROM articles_fts;"
```

### Search Test

**FTS5 test:**
```bash
sqlite3 data/articles.db \
"SELECT a.title FROM articles a 
 INNER JOIN articles_fts fts ON a.id = fts.article_id 
 WHERE articles_fts MATCH 'php*' 
 LIMIT 3;"
```

## Troubleshooting

### Časté Problémy

1. **FTS5 nevracia výsledky**
   - Skontrolovať, či FTS tabuľka obsahuje dáta: `SELECT COUNT(*) FROM articles_fts;`
   - Overiť triggery: `.schema articles_fts`
   - Testovať query priamo v SQLite

2. **Cross-database joins nefungujú**
   - To je expected behavior - použiť Author aggregate
   - Event-driven sync pre budúce synchronizácie

3. **Migration zlyhá**
   - Vymazať staré .db súbory: `rm -f data/*.db`
   - Spustiť znova: `php migrations/run_migrations.php`

4. **TypeError v Article entity**
   - Overiť, či používa AuthorId namiesto UserId
   - Skontrolovať imports v Article.php

## Budúce Rozšírenia

### Plánované Zmeny

1. **Event-Driven Synchronization**
   - Domain events pre sync medzi contexts
   - Event handlers v application layer

2. **Read Models**
   - Separátne read model pre search
   - CQRS pattern implementácia

3. **Database Sharding**
   - Rozdelenie large tabuliek
   - Partitioning podľa dátumu

4. **Backup Strategy**
   - Automated backup skripty
   - Point-in-time recovery

### Monitoring

**Database monitoring:**
```sql
-- Veľkosť databáz
SELECT page_count * page_size as size FROM pragma_page_count(), pragma_page_size();

-- Performance queries
EXPLAIN QUERY PLAN SELECT * FROM articles WHERE status = 'published';

-- FTS statistics
SELECT COUNT(*) FROM articles_fts;
```

## Related Documentation

- [DDD Author Aggregate](DDD_AUTHOR_AGGREGATE.md) - Detailný popis Author aggregate
- [FTS Implementation](FTS_IMPLEMENTATION.md) - Full-text search implementácia
- [REFAKTORIZACIA_DOKUMENTACIA.md](REFAKTORIZACIA_DOKUMENTACIA.md) - Celková refaktorizácia
- [SESSION_SECURITY_SETUP.md](SESSION_SECURITY_SETUP.md) - Session bezpečnosť

## Zhrnutie

DDD refaktorizácia priniesla:

- **Clear separation of concerns** - každý bounded context má vlastnú databázu
- **Better performance** - žiadne cross-database joins
- **Scalability** - nezávislé škálovanie kontextov
- **Maintainability** - jasný ownership a boundaries
- **Full-text search** - FTS5 implementácia pre articles
- **Data integrity** - vlastné aggregates v každom contexte
