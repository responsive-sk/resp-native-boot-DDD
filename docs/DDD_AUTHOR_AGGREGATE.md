# DDD Author Aggregate Documentation

## Overview

The Author Aggregate is a core component of the Articles Bounded Context, designed to maintain domain integrity while avoiding cross-database dependencies with the Users Bounded Context.

## Architecture

### Domain Layer

#### Author Entity (Aggregate Root)
```php
final class Author
{
    private function __construct(
        private readonly AuthorId $id,
        private readonly Username $username,
        private readonly Email $email,
        private readonly string $role
    ) {}
    
    public static function create(AuthorId $id, Username $username, Email $email, string $role = 'author'): self
    public static function reconstitute(AuthorId $id, Username $username, Email $email, string $role): self
    
    // Getters
    public function id(): AuthorId
    public function username(): Username
    public function email(): Email
    public function role(): string
    public function displayName(): string
}
```

#### Value Objects

**AuthorId**
- UUID-based identifier
- `AuthorId::fromString(string $value)`
- `AuthorId::generate()`
- `equals(AuthorId $other): bool`

**Username**
- 3-50 characters length validation
- `Username::fromString(string $value)`
- `equals(Username $other): bool`

**Email**
- Standard email format validation
- Normalizes to lowercase
- `Email::fromString(string $value)`
- `equals(Email $other): bool`

### Database Schema

```sql
CREATE TABLE IF NOT EXISTS authors (
    id TEXT PRIMARY KEY,              -- AuthorId UUID
    username TEXT UNIQUE NOT NULL,     -- Username value object
    email TEXT UNIQUE NOT NULL,       -- Email value object
    role TEXT NOT NULL DEFAULT 'author',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE IF NOT EXISTS articles (
    id TEXT PRIMARY KEY,
    title TEXT NOT NULL,
    slug TEXT UNIQUE NOT NULL,
    excerpt TEXT,
    content TEXT NOT NULL,
    author_id TEXT NOT NULL,           -- Foreign key to authors.id
    status TEXT NOT NULL DEFAULT 'draft',
    featured_image_url TEXT,
    meta_title TEXT,
    meta_description TEXT,
    published_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY (author_id) REFERENCES authors(id)
);
```

## DDD Principles Applied

### 1. Bounded Context Integrity
- Articles context owns its own Author aggregate
- No direct dependencies on Users database
- Clear separation of concerns

### 2. Aggregate Design
- **Author** is the aggregate root
- Encapsulates author-related business logic
- Maintains invariants through value objects

### 3. Ubiquitous Language
- "Author" is a first-class citizen in Articles context
- Clear terminology: Author, Username, Email, Role
- Domain experts understand the model

### 4. Data Isolation
- Articles database contains only necessary author information
- No cross-database joins required
- Each bounded context manages its own data

## Usage Examples

### Creating a New Author
```php
$authorId = AuthorId::generate();
$username = Username::fromString('john_doe');
$email = Email::fromString('john@example.com');

$author = Author::create($authorId, $username, $email, 'author');
```

### Article with Author Reference
```php
$authorId = AuthorId::fromString('40000000-0000-0000-0000-000000000001');
$title = Title::fromString('My Article');
$content = Content::fromString('Article content...');

$article = Article::create($title, $content, $authorId);
```

### Repository Hydration
```php
// Loading article with author reference
$article = Article::reconstitute(
    ArticleId::fromInt(1),
    Title::fromString('Article Title'),
    Content::fromString('Content...'),
    AuthorId::fromString('40000000-0000-0000-0000-000000000001'),
    ArticleStatus::published(),
    new DateTimeImmutable('2024-01-15'),
    new DateTimeImmutable('2024-01-15')
);
```

## Migration Strategy

### Initial Setup
```sql
-- Sample authors
INSERT OR IGNORE INTO authors (id, username, email, role) VALUES
('40000000-0000-0000-0000-000000000001', 'admin', 'admin@example.com', 'admin'),
('40000000-0000-0000-0000-000000000002', 'editor', 'editor@example.com', 'editor'),
('40000000-0000-0000-0000-000000000003', 'author', 'author@example.com', 'author');

-- Articles reference authors by ID
INSERT OR IGNORE INTO articles (id, title, slug, excerpt, content, author_id, status, published_at) VALUES
('30000000-0000-0000-0000-000000000001', 'Article Title', 'article-title', 'Excerpt...', 'Content...', '40000000-0000-0000-0000-000000000001', 'published', '2024-01-15 10:00:00');
```

## Benefits

### 1. Performance
- No cross-database queries
- Simple foreign key relationships
- Efficient joins within single database

### 2. Maintainability
- Clear domain boundaries
- Independent evolution of contexts
- Reduced coupling

### 3. Scalability
- Each bounded context can scale independently
- Database sharding friendly
- Microservices ready

### 4. Testing
- Isolated domain logic
- Simple unit tests
- Clear test boundaries

## Future Considerations

### 1. Author Synchronization
- Event-driven sync from Users to Authors context
- Eventually consistent model
- Domain events for author updates

### 2. Author Enrichment
- Add author bio, avatar fields
- Social media links
- Author statistics (article count, etc.)

### 3. Role Management
- Fine-grained permissions
- Role-based access control
- Author permissions per category

## Related Files

- `src/Domain/Blog/Entity/Author.php` - Author aggregate root
- `src/Domain/Blog/ValueObject/AuthorId.php` - Author identifier
- `src/Domain/Blog/ValueObject/Username.php` - Username validation
- `src/Domain/Blog/ValueObject/Email.php` - Email validation
- `src/Domain/Blog/Entity/Article.php` - Article with author reference
- `migrations/articles/001_init_articles_ddd.sql` - Database schema
- `src/Infrastructure/Persistence/Doctrine/DoctrineArticleRepository.php` - Repository implementation
