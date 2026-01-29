#!/bin/bash

# AGGRESSIVE vendor optimization for production
# WARNING: This removes more files. Test thoroughly before deploying!

set -e

echo "⚡ AGGRESSIVE Production optimization starting..."
echo "⚠️  This will remove extra files for maximum space savings"
echo ""

if [ ! -d "vendor/" ]; then
    echo "❌ Error: vendor/ directory not found"
    exit 1
fi

BEFORE=$(du -sh vendor/ | awk '{print $1}')
echo "📦 Before: $BEFORE"
echo ""

# Run standard optimization first
if [ -f "optimize-production.sh" ]; then
    echo "🔄 Running standard optimization first..."
    bash optimize-production.sh
    echo ""
fi

echo "⚡ Applying AGGRESSIVE optimizations..."

# Remove example/demo files
echo "  → Removing examples and demos..."
find vendor/ -type d -name "example" -exec rm -rf {} + 2>/dev/null || true
find vendor/ -type d -name "examples" -exec rm -rf {} + 2>/dev/null || true
find vendor/ -type d -name "demo" -exec rm -rf {} + 2>/dev/null || true
find vendor/ -type d -name "demos" -exec rm -rf {} + 2>/dev/null || true
find vendor/ -type d -name "sample" -exec rm -rf {} + 2>/dev/null || true

# Remove benchmark files
echo "  → Removing benchmarks..."
find vendor/ -type d -name "benchmark" -exec rm -rf {} + 2>/dev/null || true
find vendor/ -type d -name "benchmarks" -exec rm -rf {} + 2>/dev/null || true
find vendor/ -name "*Bench.php" -delete 2>/dev/null || true

# Remove stubs and fixtures
echo "  → Removing stubs and fixtures..."
find vendor/ -type d -name "stubs" -exec rm -rf {} + 2>/dev/null || true
find vendor/ -type d -name "fixtures" -exec rm -rf {} + 2>/dev/null || true
find vendor/ -type d -name "Fixtures" -exec rm -rf {} + 2>/dev/null || true

# Remove dev tools
echo "  → Removing dev tools..."
find vendor/bin -type f ! -name "doctrine" -delete 2>/dev/null || true

# Remove .dist files
echo "  → Removing .dist files..."
find vendor/ -name "*.dist" -delete 2>/dev/null || true

# Remove editor configs
echo "  → Removing editor configs..."
find vendor/ -name ".editorconfig" -delete 2>/dev/null || true
find vendor/ -name ".eslintrc*" -delete 2>/dev/null || true
find vendor/ -name ".prettierrc*" -delete 2>/dev/null || true

# Remove package manager files
echo "  → Removing package manager files..."
find vendor/ -name "package.json" -delete 2>/dev/null || true
find vendor/ -name "package-lock.json" -delete 2>/dev/null || true
find vendor/ -name "yarn.lock" -delete 2>/dev/null || true
find vendor/ -type d -name "node_modules" -exec rm -rf {} + 2>/dev/null || true

# Remove Windows-specific files
echo "  → Removing Windows files..."
find vendor/ -name "*.bat" -delete 2>/dev/null || true
find vendor/ -name "*.cmd" -delete 2>/dev/null || true

# Remove Makefile (if not needed)
echo "  → Removing build files..."
find vendor/ -name "Makefile" -delete 2>/dev/null || true
find vendor/ -name "makefile" -delete 2>/dev/null || true
find vendor/ -type d -name "build" -exec rm -rf {} + 2>/dev/null || true

# Remove backup files
echo "  → Removing backup files..."
find vendor/ -name "*~" -delete 2>/dev/null || true
find vendor/ -name "*.bak" -delete 2>/dev/null || true
find vendor/ -name "*.swp" -delete 2>/dev/null || true

# Remove large unnecessary image files (keep small icons)
echo "  → Removing large images..."
find vendor/ -name "*.png" -size +100k -delete 2>/dev/null || true
find vendor/ -name "*.jpg" -size +100k -delete 2>/dev/null || true
find vendor/ -name "*.gif" -size +100k -delete 2>/dev/null || true

# Clean up empty directories again
echo "  → Final cleanup of empty directories..."
find vendor/ -type d -empty -delete 2>/dev/null || true

echo ""
echo "⚡ AGGRESSIVE optimization complete!"
echo ""

AFTER=$(du -sh vendor/ | awk '{print $1}')
echo "📦 After:  $AFTER"
echo "💾 Before: $BEFORE"
echo ""
echo "✅ Done! Test your application thoroughly."
