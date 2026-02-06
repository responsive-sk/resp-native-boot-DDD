# Configuration Guide

This document covers all configuration options for the DDD Blog Application.

## Environment Variables

### Application Settings

```env
# Environment
APP_ENV=development              # development|staging|production
APP_DEBUG=true                   # true|false (debug mode)
APP_KEY=2cc3231b2108362be6e025ba4b528e205a9c43bf1428956885a14d700f0cc21a

# Database Configuration
DB_PATH_APP=data/app            # Path to app database
DB_PATH_ARTICLES=data/articles    # Path to articles database
DB_PATH_USERS=data/users          # Path to users database
DB_PATH_FORMS=data/forms          # Path to forms database
DB_EXTENSION=.db                 # Database file extension

# Session Configuration
SESSION_LIFETIME=3600            # Session lifetime in seconds
SESSION_NAME=resp_session          # Session cookie name
SESSION_FINGERPRINT_SALT=8d7b128ab98a11c5c29c87b9bf37dc96eb94a5f920d864b303dfd87eeda43552
SESSION_BINDING=user_agent        # Session binding method

# Security
CSRF_ENABLED=true                # Enable/disable CSRF protection

# Cloudinary Configuration
CLOUDINARY_CLOUD_NAME=your_cloud_name
CLOUDINARY_API_KEY=your_api_key
CLOUDINARY_API_SECRET=your_api_secret
CLOUDINARY_URL=cloudinary://key:secret@cloud_name

# Image Settings
IMAGE_MAX_SIZE=5242880                   # Max file size in bytes (5MB)
IMAGE_ALLOWED_TYPES=image/jpeg,image/png,image/gif,image/webp
IMAGE_DEFAULT_FOLDER=blog_uploads              # Cloudinary folder name
IMAGE_QUALITY=auto:good                    # Compression quality

# Theme
THEME_NAME=resp-front                        # Frontend theme name
```

## Database Configuration

### SQLite Database Files

Each bounded context has its own SQLite database:

#### App Database (`data/app.db`)
- **Images table** - Store uploaded image metadata
- **Audit Logs table** - Security and system events
- **Sessions table** - User session data

#### Articles Database (`data/articles.db`)
- **Articles table** - Blog posts with full-text search
- **Categories table** - Article categorization
- **Tags table** - Article tagging system
- **Authors table** - Author aggregate for articles context
- **Article_Tags table** - Many-to-many relationships
- **FTS5 table** - Full-text search virtual table

#### Users Database (`data/users.db`)
- **Users table** - User accounts and authentication
- **Roles table** - User role management

#### Forms Database (`data/forms.db`)
- **Forms table** - Dynamic form definitions
- **Form_Submissions table** - Submitted form data

### Database Connections

```php
// Database Manager handles connections automatically
use Blog\Database\DatabaseManager;

// Get specific database connection
$appConn = DatabaseManager::getConnection('app');
$articlesConn = DatabaseManager::getConnection('articles');
$usersConn = DatabaseManager::getConnection('users');
$formsConn = DatabaseManager::getConnection('forms');
```

## Security Configuration

### Password Security

```php
// Default admin password hash (for 'admin123')
$hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

// Verify password
password_verify('admin123', $hash); // Returns true
```

### Session Security

#### Fingerprinting Configuration

```php
// Session fingerprint components
$components = [
    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
    'ip_address' => $_SERVER['REMOTE_ADDR'],
    'accept_language' => $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '',
];

// Generate fingerprint
$fingerprint = hash('sha256', implode('|', $components) . $salt);
```

#### CSRF Protection

```php
// Generate CSRF token
$token = bin2hex(random_bytes(32));

// Verify CSRF token
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    // Invalid CSRF token
}
```

### Audit Logging

```php
// Audit event types
enum AuditEventType: string {
    case USER_LOGIN = 'user_login';
    case USER_LOGOUT = 'user_logout';
    case USER_REGISTER = 'user_register';
    case PASSWORD_CHANGE = 'password_change';
    case ARTICLE_CREATE = 'article_create';
    case ARTICLE_UPDATE = 'article_update';
    case ARTICLE_DELETE = 'article_delete';
    case FILE_UPLOAD = 'file_upload';
    case SECURITY_VIOLATION = 'security_violation';
}

// Log audit event
$auditLog = new AuditLog(
    AuditEventType::USER_LOGIN,
    $userId,
    $metadata,
    $ipAddress,
    $userAgent
);
```

## Image Configuration

### Cloudinary Settings

#### Account Setup

1. **Create Cloudinary Account**
   - Go to [cloudinary.com](https://cloudinary.com)
   - Sign up for free account
   - Get your cloud name, API key, and secret

2. **Environment Configuration**
   ```env
   CLOUDINARY_CLOUD_NAME=your_cloud_name
   CLOUDINARY_API_KEY=your_api_key
   CLOUDINARY_API_SECRET=your_api_secret
   CLOUDINARY_URL=cloudinary://key:secret@cloud_name
   ```

#### Upload Configuration

```php
// Cloudinary upload options
$options = [
    'resource_type' => 'auto',
    'folder' => $_ENV['IMAGE_DEFAULT_FOLDER'],
    'quality' => $_ENV['IMAGE_QUALITY'],
    'format' => 'auto',
    'secure' => true,
    'transformation' => [
        'width' => 1200,
        'height' => 800,
        'crop' => 'limit'
    ]
];
```

#### Image Transformations

```php
// Responsive image transformations
$transformations = [
    'thumbnail' => ['width' => 300, 'height' => 200, 'crop' => 'fill'],
    'medium' => ['width' => 800, 'height' => 600, 'crop' => 'limit'],
    'large' => ['width' => 1200, 'height' => 800, 'crop' => 'limit'],
];
```

## Full-Text Search Configuration

### FTS5 Setup

The application uses SQLite FTS5 for efficient full-text search:

#### Virtual Table Structure

```sql
CREATE VIRTUAL TABLE articles_fts USING fts5(
    article_id UNINDEXED,
    title,
    content,
    excerpt,
    author_name,
    tokenize='porter'
);
```

#### Search Query Examples

```php
// Basic search
$articles = $repository->search('php');

// Prefix matching
$articles = $repository->search('react*'); // Matches react, reactjs, reactive

// Boolean search
$articles = $repository->search('php AND tutorial');
$articles = $repository->search('php OR javascript');

// Field-specific search
$articles = $repository->search('title:php');
$articles = $repository->search('author:admin');

// Phrase search
$articles = $repository->search('"getting started"');
```

#### Performance Optimization

```sql
-- FTS5 automatically creates efficient indexes
-- Use LIMIT for result pagination
-- Consider using BM25 ranking for relevance
```

## Development Configuration

### Development Environment

```env
APP_ENV=development
APP_DEBUG=true
```

### Error Reporting

```php
// Development error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Production error reporting
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../data/logs/error.log');
```

### Debugging Tools

```php
// Enable query logging
$conn->getConfiguration()->setSQLLogger(new EchoSQLLogger());

// Enable profiling
$xhprof_enabled = extension_loaded('xhprof');
```

## Production Configuration

### Production Environment

```env
APP_ENV=production
APP_DEBUG=false
```

### Performance Settings

```php
// OPcache configuration
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=4000
opcache.revalidate_freq=60
opcache.validate_timestamps=0

// Database optimizations
$conn->getConfiguration()->setSQLLogger(new ProductionSQLLogger());
```

### Security Hardening

```php
// Hide PHP version
header('X-Powered-By: PHP');

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Session security
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_samesite', 'Strict');
```

## Server Configuration

### Apache (.htaccess)

```apache
# Enable compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain text/html text/xml text/css text/javascript application/javascript application/xml+rss application/json
    AddOutputFilterByType DEFLATE application/javascript
</IfModule>

# Security headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# URL rewriting
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ web.php [QSA,L]
```

### Nginx

```nginx
server {
    listen 80;
    server_name localhost;
    root /var/www/html/resp-blog/public;
    index index.php;

    # Security headers
    add_header X-Frame-Options DENY;
    add_header X-Content-Type-Options nosniff;
    add_header X-XSS-Protection "1; mode=block";

    # URL rewriting
    location / {
        try_files $uri $uri/ /web.php?$query_string;
    }

    # PHP processing
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## Testing Configuration

### PHPUnit Configuration

```xml
<!-- phpunit.xml -->
<phpunit>
    <testsuites>
        <testsuite name="Domain">
            <directory>tests/Unit/Domain</directory>
        </testsuite>
        <testsuite name="Infrastructure">
            <directory>tests/Unit/Infrastructure</directory>
        </testsuite>
        <testsuite name="Application">
            <directory>tests/Unit/Application</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

### Test Database

```bash
# Create separate test database
cp data/articles.db data/articles_test.db

# Run tests with test database
DB_PATH_ARTICLES=data/articles_test php vendor/bin/phpunit
```

## Monitoring Configuration

### Application Monitoring

```php
// Performance monitoring
$startTime = microtime(true);
// ... application logic
$endTime = microtime(true);
$executionTime = $endTime - $startTime;

// Memory usage
$memoryUsage = memory_get_peak_usage(true);

// Log performance data
error_log(sprintf(
    "Request: %s | Time: %.4fs | Memory: %s",
    $_SERVER['REQUEST_URI'],
    $executionTime,
    $this->formatBytes($memoryUsage)
));
```

### Database Monitoring

```sql
-- Check database size
SELECT 
    page_count * page_size as size_bytes,
    page_count * page_size / 1024.0 / 1024.0 as size_mb
FROM pragma_page_count(), pragma_page_size();

-- Check table statistics
SELECT 
    name,
    COUNT(*) as row_count,
    ROUND(SUM(LENGTH(content)) / 1024.0, 2) as content_kb
FROM articles;
```

## Troubleshooting

### Common Configuration Issues

#### Database Connection Errors

```bash
# Check database permissions
ls -la data/

# Check SQLite extension
php -m | grep sqlite

# Test database connection
php -r "
try {
    require_once 'boot.php';
    \$conn = Blog\Database\DatabaseManager::getConnection('articles');
    echo 'Database connection: SUCCESS';
} catch (Exception \$e) {
    echo 'Database connection: FAILED - ' . \$e->getMessage();
}
"
```

#### Cloudinary Upload Issues

```bash
# Test Cloudinary configuration
php -r "
require_once 'boot.php';
\$config = [
    'cloud_name' => \$_ENV['CLOUDINARY_CLOUD_NAME'],
    'api_key' => \$_ENV['CLOUDINARY_API_KEY'],
    'api_secret' => \$_ENV['CLOUDINARY_API_SECRET']
];
echo 'Cloudinary config: ' . json_encode(\$config);
"

# Test upload
curl -X POST \
  -F "file=@test.jpg" \
  -F "upload_preset=blog_uploads" \
  https://api.cloudinary.com/v1_1/auto/upload
```

#### Session Issues

```bash
# Check session configuration
php -r "
echo 'Session path: ' . session_save_path();
echo 'Session name: ' . session_name();
echo 'Session lifetime: ' . session_get_cookie_params()['lifetime'];
"
```

### Performance Issues

```bash
# Profile application
php -d memory_limit=512M web.php

# Check slow queries
sqlite3 data/articles.db ".timer on"
sqlite3 data/articles.db "SELECT * FROM articles WHERE content LIKE '%php%';"
sqlite3 data/articles.db ".timer off"
```

## Environment Templates

### Development (.env.development)

```env
APP_ENV=development
APP_DEBUG=true
APP_KEY=dev-key-change-in-production

# Database paths for development
DB_PATH_APP=data/app_dev
DB_PATH_ARTICLES=data/articles_dev
DB_PATH_USERS=data/users_dev
DB_PATH_FORMS=data/forms_dev

# Development-specific settings
LOG_LEVEL=debug
CACHE_ENABLED=false
```

### Staging (.env.staging)

```env
APP_ENV=staging
APP_DEBUG=false
APP_KEY=staging-key-change-in-production

# Staging database
DB_PATH_APP=data/app_staging
DB_PATH_ARTICLES=data/articles_staging
DB_PATH_USERS=data/users_staging
DB_PATH_FORMS=data/forms_staging

# Staging-specific settings
LOG_LEVEL=info
CACHE_ENABLED=true
```

### Production (.env.production)

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=production-key-32-characters-long

# Production database
DB_PATH_APP=data/app_prod
DB_PATH_ARTICLES=data/articles_prod
DB_PATH_USERS=data/users_prod
DB_PATH_FORMS=data/forms_prod

# Production-specific settings
LOG_LEVEL=error
CACHE_ENABLED=true
```

## Migration Guide

### Running Migrations

```bash
# Run all migrations
php migrations/run_migrations.php

# Run specific migration
php migrations/run_migrations.php --database=articles

# Force re-run migrations
php migrations/run_migrations.php --force
```

### Migration Structure

```
migrations/
├── app/
│   └── 001_init_app_ddd.sql
├── articles/
│   ├── 001_init_articles_ddd.sql
│   └── 002_add_fts5.sql
├── users/
│   └── 001_init_users_ddd.sql
└── forms/
    └── 001_init_forms_ddd.sql
```

---

For detailed migration information, see [DATABASE_MIGRATIONS.md](DATABASE_MIGRATIONS.md).
