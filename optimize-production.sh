#!/bin/bash

echo "🎯 Production optimization..."
du -sh vendor/ | awk '{print "Before: " $1}'

# Základný cleanup
find vendor/ -type d -name "tests" -exec rm -rf {} + 2>/dev/null
find vendor/ -type d -name "Tests" -exec rm -rf {} + 2>/dev/null
find vendor/ -type d -name "docs" -exec rm -rf {} + 2>/dev/null
find vendor/ -name "*.md" -delete 2>/dev/null
find vendor/ -name "CHANGELOG*" -delete 2>/dev/null
find vendor/ -name "README*" -delete 2>/dev/null

# Carbon locales
cd vendor/nesbot/carbon/src/Carbon/Lang/ 2>/dev/null && {
  ls -1 | grep -v "^en\.php$" | xargs rm -rf 2>/dev/null
  cd - > /dev/null
}

# Moonshine node_modules
rm -rf vendor/moonshine/*/node_modules 2>/dev/null

# Laravel stubs
rm -rf vendor/laravel/framework/types 2>/dev/null

# Symfony translations
find vendor/symfony -path "*/Resources/translations" -exec rm -rf {} + 2>/dev/null

# Git files
find vendor/ -name ".git*" -delete 2>/dev/null
find vendor/ -type d -name ".github" -exec rm -rf {} + 2>/dev/null

# Package dev files
find vendor/ -name "phpunit.xml*" -delete 2>/dev/null
find vendor/ -name "composer.lock" -delete 2>/dev/null

du -sh vendor/ | awk '{print "After:  " $1}'
echo "✅ Done!"
