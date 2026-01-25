#!/usr/bin/env bash
set -euo pipefail

# run_migration_staging.sh
# Usage: run on staging host (or CI) to deploy code and run the slug migration.
# Optional env vars:
#  STAGING_DIR - path to app (default: /var/www/chubby-blog)
#  BACKUP_CMD  - command to run to produce DB backup (e.g. "pg_dump ... > /tmp/backup.sql")
#  PHP         - path to php binary (default: php)
#  COMPOSER    - path to composer binary (default: composer)
#  STAGING_DB_DSN - optional DB DSN for quick duplicate check (sqlite:/path or mysql:host=...)

STAGING_DIR=${1:-/var/www/chubby-blog}
BACKUP_CMD=${BACKUP_CMD:-}
PHP=${PHP:-php}
COMPOSER=${COMPOSER:-composer}
STAGING_DB_DSN=${STAGING_DB_DSN:-}

echo "Deploying to staging directory: ${STAGING_DIR}"

if [ -n "${BACKUP_CMD}" ]; then
  echo "Running backup command..."
  eval "${BACKUP_CMD}"
  echo "Backup command completed."
else
  echo "No BACKUP_CMD set — ensure you have a DB backup before proceeding."
fi

echo "Updating code (main)..."
cd "${STAGING_DIR}"
git fetch origin
git checkout main
git pull origin main

echo "Installing PHP dependencies (no-dev)..."
${COMPOSER} install --no-dev --optimize-autoloader

echo "Running migration script to add and backfill slugs..."
${PHP} scripts/migrations/add_slug_to_posts.php

echo "Post-migration verification:" 
if [ -n "${STAGING_DB_DSN}" ]; then
  echo "Checking for NULL or duplicate slugs using STAGING_DB_DSN"
  if [[ "${STAGING_DB_DSN}" == sqlite:* || "${STAGING_DB_DSN}" == file:* || "${STAGING_DB_DSN}" == /* ]]; then
    # handle sqlite file path
    DBPATH="${STAGING_DB_DSN#sqlite:}"
    if [ -f "${DBPATH}" ]; then
      echo "Sample rows with NULL slugs:"
      sqlite3 "${DBPATH}" "SELECT id, title FROM posts WHERE slug IS NULL LIMIT 10;"
      echo "Count duplicate slugs (should be 0):"
      sqlite3 "${DBPATH}" "SELECT slug, COUNT(*) c FROM posts GROUP BY slug HAVING c>1;"
    else
      echo "SQLite DB path not found: ${DBPATH}"
    fi
  else
    echo "Non-sqlite DSN provided; run manual checks or set STAGING_DB_DSN to sqlite path for automated checks."
  fi
else
  echo "No STAGING_DB_DSN provided — please run manual verification queries (see checklist)."
fi

if [ -f scripts/clear_cache.php ]; then
  echo "Clearing application cache..."
  ${PHP} scripts/clear_cache.php || true
fi

if [ -x vendor/bin/phpunit ]; then
  echo "Running smoke tests (if present)..."
  vendor/bin/phpunit --colors=never --filter SmokeTest || true
fi

echo "Staging migration script finished. Verify site and logs."
