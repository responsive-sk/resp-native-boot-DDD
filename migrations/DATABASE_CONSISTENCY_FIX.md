# Database Consistency Issues and Fixes

## 🚨 IDENTIFIED INCONSISTENCIES

### 1. **Database Engine Mismatch**
- **Issue:** Mix of PostgreSQL (UUID, JSONB, gen_random_uuid()) and SQLite (AUTOINCREMENT, BLOB, DATETIME)
- **Files affected:** All migration files
- **Impact:** Incompatible SQL syntax between databases

### 2. **ID Type Inconsistencies**
- **PostgreSQL files:** `UUID PRIMARY KEY DEFAULT gen_random_uuid()`
- **SQLite files:** `INTEGER PRIMARY KEY AUTOINCREMENT` or `VARCHAR(36) PRIMARY KEY`
- **Impact:** Foreign key references break

### 3. **Data Type Mismatches**
| Field | PostgreSQL | SQLite | Issue |
|-------|------------|---------|-------|
| ID | UUID | INTEGER/VARCHAR(36) | Type mismatch |
| Timestamps | TIMESTAMP | DATETIME | Syntax difference |
| JSON | JSONB | JSON/TEXT | Feature difference |
| User ID | UUID | BLOB | Binary vs text |

### 4. **Table Structure Differences**
- **001_init_complete_database.sql:** PostgreSQL syntax
- **001_create_articles_table.sql:** SQLite syntax
- **001_create_audit_logs_table.sql:** SQLite syntax
- **002_create_categories_table.sql:** SQLite syntax
- **002_create_images_tables.sql:** PostgreSQL syntax

## 🔧 PROPOSED SOLUTIONS

### OPTION 1: UNIFY TO POSTGRESQL (RECOMMENDED)
**Benefits:**
- Better for production
- Full JSONB support
- Proper UUID handling
- Better performance for complex queries
- More robust for image management

**Migration Strategy:**
1. Keep PostgreSQL syntax in all files
2. Rename files to be consistent
3. Update application to use PostgreSQL exclusively

### OPTION 2: UNIFY TO SQLITE
**Benefits:**
- Simpler development
- No external dependencies
- File-based storage

**Drawbacks:**
- Limited JSON support
- No proper UUID support
- Not ideal for production image storage

## 📋 RECOMMENDED MIGRATION PLAN

### STEP 1: Choose Target Database
**Recommendation:** PostgreSQL for production, SQLite for development

### STEP 2: Standardize Migration Files
```
migrations/
├── 001_init_database_postgresql.sql
├── 002_create_images_postgresql.sql
├── 001_init_database_sqlite.sql
├── 002_create_images_sqlite.sql
└── README.md (database selection guide)
```

### STEP 3: Update Application Configuration
- Environment-based database selection
- Consistent entity interfaces
- Database-agnostic repository pattern

### STEP 4: Migration Execution Order
1. Drop existing inconsistent tables
2. Run unified migration for chosen database
3. Migrate existing data if needed
4. Update application code

## 🛠️ IMMEDIATE FIXES NEEDED

### 1. Fix Migration File Naming
```bash
# Current inconsistent naming
001_create_articles_table.sql    # SQLite
001_create_audit_logs_table.sql  # SQLite
001_init_complete_database.sql   # PostgreSQL
002_create_images_tables.sql    # PostgreSQL

# Proposed consistent naming
001_init_database_sqlite.sql
002_create_images_sqlite.sql
001_init_database_postgresql.sql
002_create_images_postgresql.sql
```

### 2. Fix ID Consistency
**Choose one approach:**
- **PostgreSQL:** `UUID PRIMARY KEY DEFAULT gen_random_uuid()`
- **SQLite:** `TEXT PRIMARY KEY DEFAULT (lower(hex(randomblob(4))) || '-' || lower(hex(randomblob(2))) || '-' || lower(hex(randomblob(2))) || '-' || lower(hex(randomblob(2))) || '-' || lower(hex(randomblob(6))))`

### 3. Fix Data Types
**Standardize across all files:**
- **IDs:** Consistent UUID handling
- **Timestamps:** Use consistent format
- **JSON:** Use appropriate type for target DB
- **Foreign keys:** Match primary key types

### 4. Fix Application Code
Update repositories and entities to handle:
- Consistent ID types
- Database-specific features
- Connection management
- Error handling

## 🎯 NEXT ACTIONS

1. **Decide on target database** (PostgreSQL recommended)
2. **Create unified migration files** for chosen database
3. **Update application configuration** for database selection
4. **Test migration execution** in development
5. **Update documentation** with database requirements
6. **Implement data migration** if existing data needs preservation

## 📊 COMPARISON

| Feature | PostgreSQL | SQLite |
|---------|------------|---------|
| UUID Support | ✅ Native | ❌ Manual |
| JSON Support | ✅ JSONB | ⚠️ JSON |
| Performance | ✅ High | ⚠️ Limited |
| Concurrency | ✅ Excellent | ❌ Limited |
| Image Storage | ✅ Optimized | ⚠️ File-based |
| Production Ready | ✅ Yes | ❌ Limited |
| Development Setup | ⚠️ Requires setup | ✅ Zero-config |
