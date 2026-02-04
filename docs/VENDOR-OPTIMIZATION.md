# Production Optimization Scripts

Two scripts for optimizing the `vendor/` directory before production deployment.

## Scripts

### 1. `optimize-production.sh` (Recommended)
**Safe, standard optimization** - removes development files without breaking functionality.

**What it removes:**
- Test directories (`tests/`, `Tests/`)
- Documentation (`docs/`, `*.md`, `README`, `CHANGELOG`)
- Git metadata (`.git*`, `.github/`)
- Dev tool configs (`phpunit.xml`, `phpstan.neon`, `.php-cs-fixer`)
- CI/CD configs (`.travis.yml`, `.gitlab-ci.yml`)
- Package-specific bloat (Doctrine XSD, Symfony translations, etc.)

**Usage:**
```bash
./optimize-production.sh
```

**Expected savings:** ~30-50% reduction in size

---

### 2. `optimize-production-aggressive.sh` (Advanced)
**Aggressive optimization** - maximum space savings but requires testing.

**Additionally removes:**
- Examples and demos
- Benchmarks
- Stubs and fixtures
- Most vendor binaries (keeps only essential ones)
- `.dist` configuration templates
- Editor configs (`.editorconfig`, `.eslintrc`)
- Node.js files (`package.json`, `node_modules/`)
- Windows-specific files (`.bat`, `.cmd`)
- Build files (`Makefile`, `build/`)
- Large images (>100KB)

**Usage:**
```bash
./optimize-production-aggressive.sh
```

**Expected savings:** ~40-60% reduction in size

**Warning:** Test thoroughly after running! Some packages may rely on files we remove.

---

## Recommended Workflow

### Development
```bash
# Install all dependencies including dev
composer install
```

### Production Deployment
```bash
# 1. Install production dependencies only
composer install --no-dev --optimize-autoloader

# 2. Run standard optimization (safe)
./optimize-production.sh

# 3. (Optional) Run aggressive optimization if you need more savings
# ./optimize-production-aggressive.sh

# 4. Test your application!
php public/index.php  # or run dev server
```

---

## before/After Example

**Before:**
```
vendor/: 19M
```

**After standard optimization:**
```
vendor/: ~10-13M  (30-50% smaller)
```

**After aggressive optimization:**
```
vendor/: ~8-11M  (40-60% smaller)
```

---

## What Gets Kept

Both scripts preserve:
- All PHP source code
- Composer autoloader
- Essential configuration files
- Required binaries (like `doctrine`)
- Production-necessary resources

---

## Safety Notes

1. **Always run on a COPY first** before production
2. **Test your application** after optimization
3. **Run composer install again** if something breaks
4. **Keep backups** of your vendor folder
5. **Don't commit optimized vendor/** to Git

---

## Restoring vendor/

If something breaks:
```bash
# Delete and reinstall
rm -rf vendor/
composer install --no-dev --optimize-autoloader
```

---

## Integration with Deployment

### GitHub Actions Example
```yaml
- name: Install dependencies
  run: composer install --no-dev --optimize-autoloader

- name: Optimize vendor
  run: ./optimize-production.sh
```

### Manual Deployment
```bash
# On your local machine
composer install --no-dev --optimize-autoloader
./optimize-production.sh
tar -czf app.tar.gz .

# Transfer and extract on server
scp app.tar.gz user@server:/var/www/
ssh user@server 'cd /var/www && tar -xzf app.tar.gz'
```

---

## Troubleshooting

**Q: My app broke after optimization!**
A: Run `composer install` again to restore everything, then use the standard (non-aggressive) script only.

**Q: Can I commit these to Git?**
A: Yes, commit the scripts. Never commit the optimized `vendor/` folder itself.

**Q: How much space will I save?**
A: Typically 30-60% depending on which script you use and your dependencies.

**Q: Is it safe for all projects?**
A: The standard script is safe for most projects. The aggressive one needs testing.

---

## License
MIT - Same as the project
