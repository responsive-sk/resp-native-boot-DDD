# Remote Host Theme Fix

If you're getting `"The template folder boson was not found"` error on remote host:

## Quick Fix (Choose one)

### Option 1: Update .env (Recommended)
```bash
# SSH to remote host
ssh user@remote-host

# Navigate to project
cd /path/to/project

# Create/update .env file
echo "THEME_NAME=resp-front" >> .env

# Or edit manually
nano .env
# Add: THEME_NAME=resp-front
```

### Option 2: Create Symlink
```bash
# SSH to remote host
ssh user@remote-host
cd /path/to/project

# Create symlink (adjust path as needed)
ln -s /path/to/resp-front resources/views/resp-front

# Or if you have boson theme files:
ln -s /path/to/boson resources/views/boson
```

### Option 3: Pull Latest Code
```bash
# SSH to remote host
ssh user@remote-host
cd /path/to/project

# Pull latest changes (includes backward compatibility fix)
git pull origin main

# Update dependencies
composer install --no-dev --optimize-autoloader
```

## Verify Setup

Run the theme checker script:
```bash
./scripts/check-theme.sh
```

Expected output:
```
✅ Theme 'resp-front' exists
✅ Symlink target is valid
```

## What Changed?

**Before:** Code hardcoded `'boson'` theme  
**After:** Code uses `THEME_NAME` from `.env` with auto-fallback

The latest code now:
- ✅ Auto-detects available themes
- ✅ Falls back to working theme if requested theme not found
- ✅ Registers 'boson' alias for backward compatibility
- ✅ Provides helpful error messages

## Still Having Issues?

Check the error log for more details:
```bash
tail -f /path/to/error.log
```

The new error message will show:
- Requested theme name
- Templates path
- List of available themes
- Suggestions to fix

## Production Deployment Checklist

✅ Updated `.env` with correct THEME_NAME  
✅ Created symlink to theme directory  
✅ Ran `composer install --no-dev`  
✅ Verified with `./scripts/check-theme.sh`  
✅ Tested application in browser
