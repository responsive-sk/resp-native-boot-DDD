# Database Migration Guide

## 🎯 OVERVIEW

This directory contains unified database migrations for the blog application with support for both PostgreSQL and SQLite databases.

## 📁 FILE STRUCTURE

```
migrations/
├── DATABASE_CONSISTENCY_FIX.md    # Analysis of consistency issues
├── 001_init_database_postgresql.sql # Complete PostgreSQL initialization
├── 001_init_database_sqlite.sql    # Complete SQLite initialization
├── 002_create_images_postgresql.sql # Image tables for PostgreSQL
├── 002_create_images_sqlite.sql     # Image tables for SQLite
├── README.md                        # This file
└── legacy/                          # Old inconsistent migrations (deprecated)
    ├── 001_create_articles_table.sql
    ├── 001_create_audit_logs_table.sql
    ├── 001_init_beta.sql
    ├── 001_init_complete_database.sql
    ├── 002_create_categories_table.sql
    └── 002_create_images_tables.sql
```

## 🚀 QUICK START

### For PostgreSQL (Recommended for Production)
```bash
# Run migrations in order
psql -d your_database -f migrations/001_init_database_postgresql.sql
psql -d your_database -f migrations/002_create_images_postgresql.sql
```

### For SQLite (Good for Development)
```bash
# Run migrations in order
sqlite3 your_database.db < migrations/001_init_database_sqlite.sql
sqlite3 your_database.db < migrations/002_create_images_sqlite.sql
```

## 🏗️ DATABASE SCHEMA

### Core Tables
- **users** - User accounts and profiles
- **categories** - Article categories with hierarchy
- **tags** - Article tags with colors
- **articles** - Main content with SEO metadata
- **article_tags** - Many-to-many relationship
- **comments** - Article comments with threading
- **audit_logs** - Security and compliance tracking
- **sessions** - User session management
- **settings** - Application configuration

### Image Management Tables
- **images** - Main image storage with metadata
- **image_variants** - Different sizes (thumbnail, medium, large)
- **article_images** - Images attached to articles
- **user_avatars** - User profile pictures
- **category_images** - Category cover images
- **system_images** - Logos, favicons, icons
- **image_processing_queue** - Background processing jobs

## 🔧 CONFIGURATION

### Environment Variables
```env
# Database configuration
DB_TYPE=postgresql  # or sqlite
DB_HOST=localhost
DB_PORT=5432
DB_NAME=blog_db
DB_USER=blog_user
DB_PASSWORD=blog_password

# SQLite alternative
DB_PATH=./data/blog.db
```

### Application Setup
Update your database configuration to use the appropriate migration files based on your environment:

```php
// config/database.php
return [
    'default' => env('DB_TYPE', 'sqlite'),
    
    'connections' => [
        'postgresql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_NAME', 'blog_db'),
            'username' => env('DB_USER', 'blog_user'),
            'password' => env('DB_PASSWORD', 'blog_password'),
        ],
        
        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_PATH', './data/blog.db'),
        ],
    ],
];
```

## 📊 FEATURES COMPARISON

| Feature | PostgreSQL | SQLite |
|---------|------------|---------|
| UUID Support | ✅ Native | ⚠️ Manual generation |
| JSON Support | ✅ JSONB with indexing | ⚠️ JSON as TEXT |
| Full-Text Search | ✅ Built-in | ✅ FTS5 extension |
| Performance | ✅ High concurrency | ⚠️ Single writer |
| Production Ready | ✅ Enterprise grade | ⚠️ Limited scale |
| Development Setup | ⚠️ Requires setup | ✅ Zero configuration |
| Image Storage | ✅ Optimized for binary data | ⚠️ File-based recommended |

## 🔄 MIGRATION FROM LEGACY

If you have existing data from the old inconsistent migrations:

1. **Backup your data**
2. **Export existing data** from old tables
3. **Drop old tables** (or use new database)
4. **Run new migrations**
5. **Import and transform data** to new schema

### Data Transformation Notes
- **IDs**: Convert to UUID format
- **JSON fields**: Convert to JSONB (PostgreSQL) or JSON text (SQLite)
- **Timestamps**: Ensure proper datetime format
- **Foreign keys**: Update to match new ID types

## 🧪 TESTING

### PostgreSQL Testing
```bash
# Create test database
createdb blog_test

# Run migrations
psql -d blog_test -f migrations/001_init_database_postgresql.sql
psql -d blog_test -f migrations/002_create_images_postgresql.sql

# Verify schema
psql -d blog_test -c "\dt"
```

### SQLite Testing
```bash
# Create test database
sqlite3 blog_test.db

# Run migrations
sqlite3 blog_test.db < migrations/001_init_database_sqlite.sql
sqlite3 blog_test.db < migrations/002_create_images_sqlite.sql

# Verify schema
sqlite3 blog_test.db ".tables"
```

## 🚨 IMPORTANT NOTES

1. **Choose one database type** - Don't mix PostgreSQL and SQLite migrations
2. **Run migrations in order** - 001 before 002
3. **Backup before migration** - Always backup existing data
4. **Test thoroughly** - Verify application works with new schema
5. **Update application code** - Ensure repositories match new schema

## 🛠️ TROUBLESHOOTING

### Common Issues
- **UUID generation errors** - Ensure proper database extensions
- **JSON syntax errors** - Check JSON formatting in default data
- **Foreign key constraints** - Verify table creation order
- **Permission errors** - Check database user permissions

### Solutions
- Use the provided `DATABASE_CONSISTENCY_FIX.md` for detailed analysis
- Check database logs for specific error messages
- Verify environment variables and connection strings
- Test with clean database first

## 📝 NEXT STEPS

1. **Choose your target database** (PostgreSQL recommended)
2. **Update application configuration** for database selection
3. **Run appropriate migrations** in development
4. **Test all functionality** thoroughly
5. **Update deployment scripts** for production
6. **Document your database choice** in team documentation

## 🤝 CONTRIBUTING

When adding new migrations:
1. Create both PostgreSQL and SQLite versions
2. Follow the naming convention: `XXX_description_postgresql.sql`
3. Update this README with new tables/features
4. Test both database versions
5. Update the consistency fix document if needed
