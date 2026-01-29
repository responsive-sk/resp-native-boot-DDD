#!/bin/bash

set -e  # Exit on error

echo "🎯 Production optimization starting..."
echo ""

# Check if vendor exists
if [ ! -d "vendor/" ]; then
    echo "❌ Error: vendor/ directory not found"
    exit 1
fi

# Display initial size
BEFORE=$(du -sh vendor/ | awk '{print $1}')
echo "📦 Before: $BEFORE"
echo ""

echo "🧹 Cleaning up development files..."

# === Core cleanup ===
echo "  → Removing test directories..."
find vendor/ -type d -name "tests" -exec rm -rf {} + 2>/dev/null || true
find vendor/ -type d -name "Tests" -exec rm -rf {} + 2>/dev/null || true
find vendor/ -type d -name "test" -exec rm -rf {} + 2>/dev/null || true

echo "  → Removing documentation..."
find vendor/ -type d -name "docs" -exec rm -rf {} + 2>/dev/null || true
find vendor/ -type d -name "doc" -exec rm -rf {} + 2>/dev/null || true
find vendor/ -name "*.md" -delete 2>/dev/null || true
find vendor/ -name "CHANGELOG*" -delete 2>/dev/null || true
find vendor/ -name "README*" -delete 2>/dev/null || true
find vendor/ -name "UPGRADE*" -delete 2>/dev/null || true
find vendor/ -name "LICENSE*" -delete 2>/dev/null || true

echo "  → Removing Git metadata..."
find vendor/ -name ".git*" -delete 2>/dev/null || true
find vendor/ -type d -name ".github" -exec rm -rf {} + 2>/dev/null || true

echo "  → Removing dev dependencies metadata..."
find vendor/ -name "phpunit.xml*" -delete 2>/dev/null || true
find vendor/ -name "phpcs.xml*" -delete 2>/dev/null || true
find vendor/ -name "phpstan.neon*" -delete 2>/dev/null || true
find vendor/ -name ".php-cs-fixer*" -delete 2>/dev/null || true
find vendor/ -name "psalm.xml*" -delete 2>/dev/null || true

# Remove composer.lock from packages (not root)
find vendor/ -mindepth 2 -name "composer.lock" -delete 2>/dev/null || true

echo "  → Removing CI/CD configs..."
find vendor/ -name ".travis.yml" -delete 2>/dev/null || true
find vendor/ -name ".gitlab-ci.yml" -delete 2>/dev/null || true
find vendor/ -name "appveyor.yml" -delete 2>/dev/null || true
find vendor/ -name ".scrutinizer.yml" -delete 2>/dev/null || true

# === Package-specific cleanup ===
echo "  → Package-specific optimizations..."

# Doctrine: Remove XML/YAML schema files if not used
if [ -d "vendor/doctrine" ]; then
    echo "    • Doctrine: Removing schema files..."
    find vendor/doctrine -name "*.xsd" -delete 2>/dev/null || true
fi

# Symfony: Remove translations if English-only
if [ -d "vendor/symfony" ]; then
    echo "    • Symfony: Keeping only English translations..."
    find vendor/symfony -path "*/Resources/translations" -type d | while read dir; do
        if [ -d "$dir" ]; then
            cd "$dir" 2>/dev/null && {
                ls -1 | grep -v "^\\..*" | grep -v "^en\\..*" | grep -v "^en_.*" | xargs rm -rf 2>/dev/null || true
                cd - > /dev/null
            }
        fi
    done
fi

# Plates: Remove examples if present
if [ -d "vendor/league/plates" ]; then
    echo "    • Plates: Removing examples..."
    rm -rf vendor/league/plates/example 2>/dev/null || true
fi

# PSR packages: Usually small, but clean anyway
echo "    • PSR: Cleaning..."
find vendor/psr -name "*.md" -delete 2>/dev/null || true

# Ramsey UUID: Remove build artifacts
if [ -d "vendor/ramsey/uuid" ]; then
    echo "    • Ramsey UUID: Removing build files..."
    rm -rf vendor/ramsey/uuid/build 2>/dev/null || true
fi

# === Remove empty directories ===
echo "  → Removing empty directories..."
find vendor/ -type d -empty -delete 2>/dev/null || true

echo ""
echo "✅ Optimization complete!"
echo ""

# Display final size and savings
AFTER=$(du -sh vendor/ | awk '{print $1}')
echo "📦 After:  $AFTER"
echo "💾 Before: $BEFORE"
echo ""

# Try to show percentage saved (if numeric)
echo "📊 Run 'du -sh vendor/' to verify final size"
