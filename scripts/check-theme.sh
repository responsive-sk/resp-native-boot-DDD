#!/bin/bash

# Theme Setup Checker - verify theme configuration

echo "🔍 Theme Configuration Check"
echo "================================"
echo ""

# Check .env file
if [ -f ".env" ]; then
    echo "✅ .env file exists"
    
    THEME_NAME=$(grep "^THEME_NAME=" .env | cut -d'=' -f2)
    if [ -n "$THEME_NAME" ]; then
        echo "   THEME_NAME=$THEME_NAME"
    else
        echo "⚠️  THEME_NAME not set in .env (will default to 'resp-front')"
        THEME_NAME="resp-front"
    fi
else
    echo "⚠️  .env file not found (will use defaults)"
    THEME_NAME="resp-front"
fi

echo ""

# Check resources/views directory
if [ ! -d "resources/views" ]; then
    echo "❌ resources/views directory not found!"
    exit 1
fi

echo "📁 Available themes in resources/views/:"
ls -la resources/views/ | grep -v "^total" | grep -v "^\." | tail -n +2 | awk '{print "   " $9 " -> " (NF>10 ? $(NF-2) " " $(NF-1) " " $NF : "")}'

echo ""

# Check if configured theme exists
THEME_PATH="resources/views/$THEME_NAME"
if [ -d "$THEME_PATH" ]; then
    echo "✅ Theme '$THEME_NAME' exists at: $THEME_PATH"
    
    # Check if it's a symlink
    if [ -L "$THEME_PATH" ]; then
        TARGET=$(readlink "$THEME_PATH")
        echo "   → Symlink pointing to: $TARGET"
        
        # Check if symlink target exists
        if [ -e "$THEME_PATH" ]; then
            echo "   ✅ Symlink target is valid"
        else
            echo "   ❌ Symlink target does not exist!"
            exit 1
        fi
    else
        echo "   → Regular directory"
    fi
    
    # Check for required subdirectories
    echo ""
    echo "   Required directories:"
    for dir in "app" "partials" "layout" "error"; do
        if [ -d "$THEME_PATH/$dir" ]; then
            echo "      ✅ $dir/"
        else
            echo "      ⚠️  $dir/ (optional, may cause errors if templates reference it)"
        fi
    done
else
    echo "❌ Theme '$THEME_NAME' NOT FOUND at: $THEME_PATH"
    echo ""
    echo "Solutions:"
    echo "1. Create symlink: ln -s /path/to/theme resources/views/$THEME_NAME"
    echo "2. Change THEME_NAME in .env to one of the available themes above"
    echo "3. Copy theme files to: $THEME_PATH"
    exit 1
fi

echo ""
echo "================================"
echo "✅ Theme configuration looks good!"
echo ""
echo "To test: php -S localhost:8000 -t public"
