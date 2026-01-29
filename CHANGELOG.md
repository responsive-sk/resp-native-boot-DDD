# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added (2026-01-29)
- UUID support for User entity via `ramsey/uuid` package
- Environment variable support via `vlucas/phpdotenv` package
- Migration script `scripts/migrate_to_uuid.php` for converting existing data
- Extracted routing configuration to `config/routes.php`
- `.env.example` template file

### Changed (2026-01-29)
- **BREAKING**: User IDs migrated from INTEGER to UUID (BLOB, 16 bytes)
- **BREAKING**: `UserId` Value Object now uses UUIDs instead of integers
  - Removed: `fromInt()`, `toInt()`
  - Added: `generate()`, `fromString()`, `toString()`, `fromBytes()`, `toBytes()`
- Database schema updated:
  - `users.id`: INTEGER → BLOB (UUID)
  - `articles.user_id`: INTEGER → BLOB (UUID)
  - `users.password`: renamed to `password_hash`
- Session storage: `$_SESSION['user_id']` now stores UUID string
- `CreateArticle` use case: `$authorId` parameter changed from `int` to `string`
- Article API responses: `author_id` field now returns UUID string
- User entity: ID generated in domain via `UserId::generate()` (no longer auto-increment)

### Fixed (2026-01-29)
- Resolved "function uses too many types" error in `services_ddd.php`
- Fixed login/registration flow to work with UUID sessions
- Restored missing getter methods in User entity
- Fixed column name mismatch in UserRepository (`password` → `password_hash`)

### Migration Guide (2026-01-29)
**For existing installations:**

1. Install new dependencies:
   ```bash
   composer install
   ```

2. Run the UUID migration script:
   ```bash
   php scripts/migrate_to_uuid.php
   ```
   
   This will:
   - Convert all user IDs from INTEGER to UUID
   - Update all article references to use new UUIDs
   - Preserve existing data (creates UUID mapping)
   - Rebuild full-text search index

3. Optional: Create `.env` file from `.env.example`:
   ```bash
   cp .env.example .env
   ```

**Breaking changes for integrations:**
- Any external systems storing user IDs must update to handle UUID strings
- API clients should expect `author_id` as UUID string (e.g., `"a05a8d5c-96a6-467a-96cd-6cb7a0c897d4"`)
- Session handling code must treat `user_id` as string, not integer

---

## [0.1.0] - 2025-10-13

Initial DDD architecture implementation.

### Added
- Domain-Driven Design architecture
- User domain with authentication
- Blog domain with Article entity
- Mark (operator) role and dashboard
- API endpoints for articles
- Doctrine DBAL repository implementations
- Plates template renderer
- FastRoute routing
- SQLite per-domain databases
