# Session Security Setup Guide

## 🔐 SESSION_FINGERPRINT_SALT Configuration

### Why This Matters

The `SESSION_FINGERPRINT_SALT` is a critical security feature that:
- Prevents session hijacking attacks
- Creates unique session fingerprints per environment
- Adds an additional layer of security beyond session IDs

### Quick Start

1. **Copy `.env.example` to `.env`:**
   ```bash
   cp .env.example .env
   ```

2. **Generate secure salt:**
   ```bash
   php -r "echo bin2hex(random_bytes(32)) . PHP_EOL;"
   ```

3. **Update `.env` file:**
   ```env
   SESSION_FINGERPRINT_SALT=your-generated-salt-here
   ```

4. **Load in application:**
   ```php
   use Core\Infrastructure\Config\EnvLoader;
   
   // In bootstrap/public/index.php
   EnvLoader::load(__DIR__ . '/..');
   ```

### Generating Salts for Different Environments

```bash
# Development
echo "SESSION_FINGERPRINT_SALT=$(php -r 'echo bin2hex(random_bytes(32));')"

# Staging  
echo "SESSION_FINGERPRINT_SALT=$(php -r 'echo bin2hex(random_bytes(32));')"

# Production
echo "SESSION_FINGERPRINT_SALT=$(php -r 'echo bin2hex(random_bytes(32));')"
```

### ⚠️ Security Best Practices

1. **Different salts per environment:**
   ```
   Development:  abc123...
   Staging:      def456...
   Production:   ghi789...
   ```

2. **Never commit `.env` to version control:**
   ```bash
   echo ".env" >> .gitignore
   git rm --cached .env  # Remove if already committed
   ```

3. **Create `.env.example` for team:**
   ```env
   SESSION_FINGERPRINT_SALT=change-me-in-production
   ```

4. **Rotate salts periodically:**
   - After security incidents
   - Every 6-12 months
   - When team members leave

### Environment Variables Reference

```env
# Required
SESSION_FINGERPRINT_SALT=your-secret-salt-here

# Optional (with defaults)
SESSION_TIMEOUT=1800              # 30 minutes
SESSION_COOKIE_NAME=RESP_SESSION
SESSION_COOKIE_SECURE=false       # true in production
SESSION_COOKIE_HTTPONLY=true
SESSION_COOKIE_SAMESITE=Lax
```

### Implementation in Code

```php
// 1. Load environment
use Core\Infrastructure\Config\EnvLoader;

EnvLoader::load(__DIR__ . '/..');

// 2. Create middleware
use Infrastructure\Http\Middleware\SessionTimeoutMiddleware;

$sessionMiddleware = SessionTimeoutMiddleware::fromEnv();

// 3. Apply to routes
$sessionMiddleware->handle(function() {
    // Your application logic
});
```

### Troubleshooting

**Error: "SESSION_FINGERPRINT_SALT not configured"**
- Ensure `.env` file exists in project root
- Check `SESSION_FINGERPRINT_SALT` is set in `.env`
- Verify `EnvLoader::load()` is called before middleware

**Error: "Required environment variable not set"**
- Generate new salt: `php -r "echo bin2hex(random_bytes(32));"`
- Add to `.env` file
- Restart web server

**Sessions not working after deployment**
- Different salt between environments = sessions won't transfer
- This is intentional for security
- Users will need to log in again after deployment

### Testing

```php
// Test in development
EnvLoader::load(__DIR__);

$salt = EnvLoader::getRequired('SESSION_FINGERPRINT_SALT');
echo "Salt configured: " . substr($salt, 0, 16) . "...\n";
```

### Docker/Container Setup

```dockerfile
# In Dockerfile
ENV SESSION_FINGERPRINT_SALT=${SESSION_FINGERPRINT_SALT}

# Or in docker-compose.yml
environment:
  - SESSION_FINGERPRINT_SALT=${SESSION_FINGERPRINT_SALT}
```

### CI/CD Integration

```yaml
# GitHub Actions example
- name: Setup environment
  run: |
    echo "SESSION_FINGERPRINT_SALT=${{ secrets.SESSION_SALT }}" > .env
```

### Migration from Hardcoded Salt

If you're migrating from a hardcoded salt:

1. Set the OLD salt in `.env` temporarily
2. Deploy to all environments
3. After 24-48 hours, generate NEW salts
4. Update `.env` in all environments
5. Users will be logged out (expected)

### Performance Notes

The middleware includes optimization:
- Activity updates throttled to once per 60 seconds
- Reduces session writes by ~98%
- Minimal performance impact

### Related Documentation

- `docs/SECURITY.md` - Full security guidelines
- `docs/DEPLOYMENT.md` - Production deployment
- `docs/ARCHITECTURE.md` - System architecture

---

## 📝 Checklist

Before deploying to production:

- [ ] `.env` file created
- [ ] `SESSION_FINGERPRINT_SALT` generated and set
- [ ] Different salts for dev/staging/prod
- [ ] `.env` added to `.gitignore`
- [ ] `.env.example` created for team
- [ ] Session settings configured
- [ ] Tested locally
- [ ] Tested in staging
- [ ] Team knows users will be logged out after first deploy

---

**Questions?** Check the troubleshooting section or open an issue.
