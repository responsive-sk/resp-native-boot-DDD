# Code Formatting Guide

This document explains the code formatting standards and tools used in this DDD PHP project.

## Tools Used

### PHP CS Fixer
We use [PHP CS Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) to automatically format PHP code according to PSR-12 and custom DDD-specific rules.

### PHPStan
[PHPStan](https://phpstan.org/) is used for static analysis to catch potential bugs and ensure code quality.

## Configuration

### PHP CS Fixer Configuration
The configuration is located in `.php-cs-fixer.php` and includes:

- **PSR-12** compliance
- **DDD-specific rules** (entities are not final, etc.)
- **Import ordering** (alphabetical)
- **Array syntax** (short syntax `[]`)
- **Trailing commas** in multiline arrays

### Custom Rules Applied
```php
'final_class' => false, // DDD entities are not final
'final_public_method_for_abstract_class' => false,
'final_internal_class' => false,
'self_static_accessor' => true, // Use $this instead of self::
'phpdoc_to_param_type' => false, // If not using typed properties everywhere yet
'phpdoc_to_property_type' => false,
'phpdoc_to_return_type' => false,
```

## Available Commands

### Quick Commands
```bash
# Quick format all files
composer cs

# Check what would be changed (dry run)
composer cs:dry

# Detailed check with verbose output
composer cs:check

# Format with risky rules enabled
composer cs:risky

# Clear CS Fixer cache
composer cs:cache:clear
```

### Standard Commands
```bash
# Standard format
composer cs-fix

# Check without formatting
composer cs-check

# Show diff of changes
composer cs-fix:diff

# Lint without cache
composer lint
```

### Static Analysis
```bash
# Standard PHPStan analysis
composer stan

# Strict PHPStan analysis (max level)
composer stan:strict
```

### Pre-commit Workflows
```bash
# Quick pre-commit (CS + tests)
composer pre-commit

# Strict pre-commit (CS + PHPStan + tests)
composer pre-commit:strict

# Full CI pipeline
composer ci
```

## Git Integration

### Pre-commit Hook Example
Create `.git/hooks/pre-commit`:
```bash
#!/bin/bash
echo "🔧 Running PHP CS Fixer..."
composer cs
git add -u
echo "✅ Code formatted!"
```

Make it executable:
```bash
chmod +x .git/hooks/pre-commit
```

## IDE Integration

### VSCode
Add to `.vscode/settings.json`:
```json
{
    "php-cs-fixer.config": ".php-cs-fixer.php",
    "php-cs-fixer.executablePath": "vendor/bin/php-cs-fixer",
    "editor.formatOnSave": true,
    "php-cs-fixer.onsave": true
}
```

### PhpStorm
1. Install PHP CS Fixer plugin
2. Set path to `vendor/bin/php-cs-fixer`
3. Configure to use `.php-cs-fixer.php`

## File Structure

### Configuration Files
- `.php-cs-fixer.php` - CS Fixer configuration
- `.php-cs-fixer.cache` - CS Fixer cache file
- `composer.json` - Contains all formatting scripts

### Target Directories
CS Fixer processes:
- `src/` - All PHP source files
- `config/` - Configuration files

### Excluded Directories
- `vendor/` - Dependencies
- `node_modules/` - Node dependencies
- `data/` - Data files
- `scripts/` - Build scripts
- `resources/views/` - View templates

## Best Practices

### Before Committing
1. Run `composer pre-commit` to ensure code quality
2. Check for any formatting issues with `composer cs:dry`
3. Run tests with `composer test`

### During Development
- Use `composer cs` frequently to keep code formatted
- Use `composer cs:dry` to review changes before applying
- Clear cache with `composer cs:cache:clear` if experiencing issues

### Team Collaboration
- All team members should use the same CS Fixer version
- Commit the `.php-cs-fixer.cache` file to ensure consistency
- Use pre-commit hooks to enforce formatting standards

## Troubleshooting

### Common Issues

#### CS Fixer Not Working
```bash
# Clear cache and try again
composer cs:cache:clear
composer cs
```

#### PHPStan Errors
- Check for missing type hints
- Ensure all dependencies are properly declared
- Run `composer stan` to see detailed error messages

#### Version Conflicts
Ensure you're running the correct PHP version:
```bash
php --version  # Should be 8.2-8.5
```

### Getting Help
- Check [PHP CS Fixer Documentation](https://cs.symfony.com/)
- Check [PHPStan Documentation](https://phpstan.org/)
- Review existing issues in the project repository

## Version Information

- **PHP CS Fixer**: ^3.93
- **PHPStan**: ^1.0
- **PHP Version**: 8.2 - 8.5

## Contributing

When contributing to this project:
1. Follow the existing code style
2. Run `composer pre-commit:strict` before submitting
3. Ensure all tests pass
4. Update documentation if adding new formatting rules
