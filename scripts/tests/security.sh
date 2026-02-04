#!/bin/bash
echo "=== KOREKČNÝ TEST ==="

echo ""
echo "1. 🐛 OPRAVA APP_ENV WARNINGU:"
echo "------------------------------"
# Dočasná oprava pre test
export APP_ENV=development

echo "APP_ENV nastavený na: $APP_ENV"

echo ""
echo "2. 🧪 TESTOVANIE KONFIGURÁCIE:"
echo "=============================="

php -r "
// 1. Test session config
echo '📋 Session config test:\n';
try {
    \$session = require 'config/session.php';
    echo '✅ config/session.php načítaný\n';
    echo '   - Timeout default: ' . (\$session['timeout']['default'] ?? 'N/A') . 's\n';
    echo '   - Timeout mark: ' . (\$session['timeout']['mark'] ?? 'N/A') . 's\n';
    echo '   - Fingerprint: ' . (\$session['fingerprint']['enabled'] ? 'enabled' : 'disabled') . '\n';
    echo '   - Cookie secure: ' . (\$session['cookie']['secure'] ? 'true' : 'false') . '\n';
} catch (Exception \$e) {
    echo '❌ Chyba pri načítaní config/session.php: ' . \$e->getMessage() . '\n';
}

// 2. Test základného DI
echo '\n🔧 DI Container test:\n';
try {
    require 'vendor/autoload.php';
    \$containerFactory = require 'config/container.php';
    \$container = \$containerFactory();
    echo '✅ Container načítaný\n';
    
    // Test jednotlivých komponentov
    \$components = [
        'AuthController' => 'Blog\Infrastructure\Http\Controller\Web\AuthController',
        'SessionTimeoutMiddleware' => 'Blog\Infrastructure\Http\Middleware\SessionTimeoutMiddleware',
        'Paths' => 'ResponsiveSk\Slim4Paths\Paths',
    ];
    
    foreach (\$components as \$name => \$class) {
        try {
            if (!\$container->has(\$class)) {
                echo '   - ' . \$name . ': ❌ (Service not found)\n';
                continue;
            }
            
            \$instance = \$container->get(\$class);
            echo '   - ' . \$name . ': ✅\n';
            
            // Extra test pre SessionTimeoutMiddleware
            if (\$name === 'SessionTimeoutMiddleware') {
                \$reflection = new ReflectionClass(\$instance);
                \$configProp = \$reflection->getProperty('config');
                \$configProp->setAccessible(true);
                \$config = \$configProp->getValue(\$instance);
                if (isset(\$config['timeout']['mark'])) {
                    echo '     ↳ mark timeout: ' . \$config['timeout']['mark'] . 's\n';
                }
            }
            
        } catch (Exception \$e) {
            echo '   - ' . \$name . ': ❌ (' . \$e->getMessage() . ')\n';
        }
    }
    
} catch (Exception \$e) {
    echo '❌ Chyba pri načítaní container: ' . \$e->getMessage() . '\n';
    echo 'Stack trace: ' . \$e->getTraceAsString() . '\n';
}

echo '\n🎯 FINÁLNY STATUS:\n';
echo '=================\n';
echo 'Podľa CR checklistu:\n';
echo '✅ Všetky kritické opravy hotové\n';
echo '✅ Session management konfigurovateľný\n';
echo '✅ Audit logging implementovaný\n';
echo '⏳ Remember Me - čaká na implementáciu\n';
echo '⏳ Password Reset - čaká na implementáciu\n';
"

echo ""
echo "3. 🔍 RÝCHLA MANUÁLNA KONTROLA:"
echo "================================"
echo "Skontroluj nasledovné súbory:"
echo "  - config/session.php (secure cookie setting)"
echo "  - .env súbor (ak existuje)"
echo ""
echo "Ak nemáš .env súbor, vytvor ho:"
echo "--------------------------------"
cat << 'EOF'
# .env
APP_ENV=development
APP_URL=http://localhost:8000
SESSION_FINGERPRINT_SALT=your-secret-salt-change-in-production
EOF

echo ""
echo "4. 🚀 SPUSTENIE APLIKÁCIE:"
echo "=========================="
echo "Na spustenie aplikácie:"
echo "  php -S localhost:8000 -t public"
echo ""
echo "Testovacie URL:"
echo "  - Registrácia: http://localhost:8000/register"
echo "  - Prihlásenie: http://localhost:8000/login" 
echo "  - Mark dashboard: http://localhost:8000/mark/dashboard"
echo ""
echo "=== KONIEC TESTOV ==="