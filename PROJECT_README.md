# DDD Blog Application

A modern PHP blog application built with Domain-Driven Design principles.

## Features

- **DDD Architecture** with 4 bounded contexts
- **Multi-Database** setup (app, articles, users, forms)
- **Full-Text Search** with SQLite FTS5
- **Cloudinary Integration** for image management
- **Audit Logging** for security events
- **Session Management** with fingerprinting
- **Responsive Frontend** integration

## Architecture

### Bounded Contexts

The application follows Domain-Driven Design with separate bounded contexts:

```
src/Domain/
├── Blog/           # Articles, Categories, Tags, Authors
├── User/           # User management, Authentication
├── Common/         # Shared value objects and events
└── Forms/          # Contact forms, Submissions
```

### Database Structure

```
data/
├── app.db      # Images, Audit Logs, Sessions
├── articles.db # Articles, Categories, Tags, FTS5
├── users.db    # User accounts, Authentication
└── forms.db    # Forms and Submissions
```

## Quick Start

### 1. Installation

```bash
# Clone repository
git clone <repository>
cd resp-blog

# Run installer
php scripts/install.php

# Or manual setup
composer install
cp .env.example .env
# Edit .env with your configuration
php migrations/run_migrations.php
```

### 2. Development Server

```bash
# Start development server
php -S localhost:8000 web.php

# Access application
http://localhost:8000
```

### 3. Default Credentials

```
Username: admin
Email: admin@example.com
Password: admin123
```

**Important**: Change the default password immediately after first login.

## Project Structure

```
resp-blog/
├── src/
│   ├── Domain/           # Domain layer (entities, value objects, events)
│   ├── Application/       # Application services and use cases
│   ├── Infrastructure/    # External integrations (database, email, storage)
│   └── Presentation/     # Controllers and routing
├── config/              # Configuration files
├── migrations/           # Database migrations
├── scripts/             # Installation and utility scripts
├── docs/                # Documentation
├── public/               # Web root
├── data/                # SQLite databases and uploads
└── vendor/              # Composer dependencies
```

## Key Components

### Domain Layer

- **Article Entity** with Author aggregate
- **Category & Tag** entities
- **Author Aggregate** (separate from User context)
- **Domain Events** for event-driven architecture
- **Value Objects** for type safety

### Infrastructure Layer

- **Doctrine DBAL** for database abstraction
- **SQLite FTS5** for full-text search
- **Cloudinary SDK** for image management
- **Session Management** with security features

### Application Layer

- **Use Cases** for business operations
- **Services** for cross-cutting concerns
- **Repositories** for data persistence

## Full-Text Search

The application implements SQLite FTS5 for efficient article searching:

### Features

- **Prefix Matching** - `php` matches `php`, `php8`, `phpunit`
- **Multi-term Search** - `react hooks tutorial`
- **Boolean Operators** - `AND`, `OR`, `NOT`
- **Field-specific Search** - `title:php`, `author:admin`
- **BM25 Ranking** - automatic relevance scoring

### Usage

```php
// Search articles
$articles = $articleRepository->search('php tutorial');

// Search by author
$articles = $articleRepository->search('author:admin');

// Phrase search
$articles = $articleRepository->search('"getting started"');
```

## Security Features

### Authentication & Authorization

- **Password Hashing** with bcrypt
- **Session Management** with fingerprinting
- **CSRF Protection** for form submissions
- **Audit Logging** for security events

### Session Security

```php
// Session configuration
SESSION_LIFETIME=3600
SESSION_FINGERPRINT_SALT=<random-salt>
SESSION_BINDING=user_agent
```

## Image Management

### Cloudinary Integration

- **Automatic Upload** to Cloudinary CDN
- **Image Optimization** and format conversion
- **Responsive Images** with multiple sizes
- **Local Fallback** for development

### Configuration

```env
# Cloudinary settings
CLOUDINARY_CLOUD_NAME=your_cloud_name
CLOUDINARY_API_KEY=your_api_key
CLOUDINARY_API_SECRET=your_api_secret

# Image settings
IMAGE_MAX_SIZE=5242880
IMAGE_ALLOWED_TYPES=image/jpeg,image/png,image/gif,image/webp
IMAGE_QUALITY=auto:good
```

## Development

### Code Quality

- **Strict Types** for type safety
- **PSR Standards** compliance
- **Domain-Driven Design** principles
- **Test Coverage** with unit and integration tests

### Coding Standards

- **PSR-12** for autoloading
- **PSR-4** for HTTP interfaces
- **PSR-7** for HTTP messages
- **PSR-11** for container interfaces

## Documentation

### Available Documentation

- `docs/DDD_AUTHOR_AGGREGATE.md` - Author aggregate design
- `docs/FTS_IMPLEMENTATION.md` - Full-text search implementation
- `docs/DATABASE_MIGRATIONS.md` - Database migration guide
- `docs/SESSION_SECURITY_SETUP.md` - Session security configuration

### API Documentation

- Domain entities documentation
- Repository interfaces
- Service layer documentation
- Migration file documentation

## Deployment

### Production Setup

```bash
# Environment setup
export APP_ENV=production
export APP_DEBUG=false

# Database setup
php migrations/run_migrations.php

# Asset optimization
composer install --no-dev --optimize-autoloader
```

### Security Considerations

- **Environment Variables** - never commit sensitive data
- **Database Security** - proper file permissions
- **Session Security** - secure cookie settings
- **Input Validation** - sanitize all user inputs
- **Error Handling** - don't expose sensitive information

## Performance

### Database Optimization

- **FTS5 Indexes** for fast text search
- **Proper Indexing** on foreign keys and frequently queried columns
- **Connection Pooling** for database efficiency
- **Query Optimization** with prepared statements

### Caching Strategy

- **Application Cache** for frequently accessed data
- **Image CDN** via Cloudinary
- **Static Asset Caching** with proper headers
- **Session Caching** for user sessions

## Testing

### Test Suite

```bash
# Run all tests
composer test

# Run specific test
composer test -- --filter=ArticleTest

# Generate coverage report
composer test -- --coverage-html
```

### Test Structure

```
tests/
├── Unit/           # Domain layer tests
├── Integration/     # Repository tests
├── Functional/      # Application tests
└── E2E/           # End-to-end tests
```

## Troubleshooting

### Common Issues

**Migration Errors**
```bash
# Clear databases and re-run
rm -rf data/*.db
php scripts/install.php
```

**Permission Issues**
```bash
# Fix directory permissions
chmod -R 755 data/
chmod -R 755 public/uploads/
```

**Search Not Working**
```bash
# Rebuild FTS index
sqlite3 data/articles.db "DELETE FROM articles_fts;"
sqlite3 data/articles.db "INSERT INTO articles_fts SELECT a.id, a.title, a.content, a.excerpt, auth.username FROM articles a JOIN authors auth ON a.author_id = auth.id;"
```

## Contributing

### Development Workflow

1. Fork the repository
2. Create feature branch
3. Make changes with tests
4. Run test suite
5. Submit pull request

### Code Style

- Follow PSR-12 coding standards
- Use strict types everywhere
- Write meaningful commit messages
- Add documentation for new features

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For questions and support:

- Check the documentation in `docs/` directory
- Review existing issues and discussions
- Follow the contribution guidelines

---

**Built with PHP 8.1+ and Domain-Driven Design principles.**
