<?php
// scripts/install_complete.php

declare(strict_types=1);

require_once __DIR__ . '/../boot.php';

use Blog\Database\DatabaseManager;

class Installer
{
    private array $steps = [
        'check_requirements',
        'setup_environment',
        'create_directories',
        'run_migrations',
        'verify_installation',
        'create_admin_user'
    ];

    public function run(): void
    {
        echo "=== DDD Blog Application Installer ===\n\n";
        echo "This will set up your multi-database DDD application.\n";
        
        if (!$this->confirm("Do you want to continue?")) {
            exit("Installation cancelled.\n");
        }

        foreach ($this->steps as $step) {
            $this->{$step}();
        }

        $this->showSummary();
    }

    private function check_requirements(): void
    {
        echo "=== Checking Requirements ===\n";

        $requirements = [
            'PHP >= 8.1' => version_compare(PHP_VERSION, '8.1.0', '>='),
            'PDO SQLite' => extension_loaded('pdo_sqlite'),
            'JSON extension' => extension_loaded('json'),
            'MBString extension' => extension_loaded('mbstring'),
            'OpenSSL extension' => extension_loaded('openssl'),
            'data directory writable' => is_writable(__DIR__ . '/../data') || !file_exists(__DIR__ . '/../data'),
            'Composer autoload' => file_exists(__DIR__ . '/../vendor/autoload.php'),
        ];

        $allPassed = true;
        foreach ($requirements as $name => $result) {
            echo $result ? "✓ " : "✗ ";
            echo "$name\n";
            
            if (!$result) {
                $allPassed = false;
            }
        }

        if (!$allPassed) {
            echo "\nSome requirements are not met. Please fix them and try again.\n";
            exit(1);
        }

        echo "\n";
    }

    private function setup_environment(): void
    {
        echo "=== Environment Setup ===\n";

        $envFile = __DIR__ . '/../.env';
        $envExample = __DIR__ . '/../.env.example';

        if (!file_exists($envFile)) {
            if (file_exists($envExample)) {
                copy($envExample, $envFile);
                echo "✓ .env file created from .env.example\n";
            } else {
                $this->createDefaultEnv($envFile);
                echo "✓ Default .env file created\n";
            }
            
            echo "\nIMPORTANT: Please edit .env file with your actual configuration!\n";
            if (!$this->confirm("Have you edited .env file?")) {
                echo "Please edit .env file before continuing.\n";
                exit(1);
            }
        } else {
            echo "✓ .env file already exists\n";
        }

        // Reload environment variables
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                if (strpos($line, '=') !== false) {
                    [$key, $value] = explode('=', $line, 2);
                    $_ENV[trim($key)] = trim($value);
                }
            }
        }

        echo "\n";
    }

    private function createDefaultEnv(string $path): void
    {
        $content = <<<ENV
# Application
APP_ENV=development
APP_DEBUG=true
APP_KEY=2cc3231b2108362be6e025ba4b528e205a9c43bf1428956885a14d700f0cc21a

# Database Configuration (SQLite files)
DB_PATH_APP=data/app
DB_PATH_ARTICLES=data/articles
DB_PATH_USERS=data/users
DB_PATH_FORMS=data/forms
DB_EXTENSION=.db

# Session
SESSION_LIFETIME=3600
SESSION_NAME=resp_session
SESSION_FINGERPRINT_SALT=8d7b128ab98a11c5c29c87b9bf37dc96eb94a5f920d864b303dfd87eeda43552
SESSION_BINDING=user_agent

# Security
CSRF_ENABLED=true

# Cloudinary (required for image uploads)
CLOUDINARY_CLOUD_NAME=your_cloud_name
CLOUDINARY_API_KEY=your_api_key
CLOUDINARY_API_SECRET=your_api_secret
CLOUDINARY_URL=cloudinary://key:secret@cloud_name

# Image Settings
IMAGE_MAX_SIZE=5242880
IMAGE_ALLOWED_TYPES=image/jpeg,image/png,image/gif,image/webp
IMAGE_DEFAULT_FOLDER=blog_uploads
IMAGE_QUALITY=auto:good

# Theme
THEME_NAME=resp-front
ENV;

        file_put_contents($path, $content);
    }

    private function create_directories(): void
    {
        echo "=== Creating Directories ===\n";

        $base = __DIR__ . '/..';
        $directories = [
            'data',
            'data/logs',
            'data/cache',
            'data/uploads',
            'data/sessions',
            'data/backups',
            'var',
            'var/log',
            'var/cache',
            'public/uploads',
        ];

        foreach ($directories as $dir) {
            $path = $base . '/' . $dir;
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
                echo "✓ Created: $dir/\n";
            } else {
                echo "✓ Exists: $dir/\n";
            }
        }

        echo "\n";
    }

    private function run_migrations(): void
    {
        echo "=== Running Database Migrations ===\n";

        try {
            // Include and run migrations
            require_once __DIR__ . '/../migrations/run_migrations.php';
            
            $runner = new MigrationRunner();
            $runner->run(true); // Force run
            
            echo "✓ All migrations completed\n";
            
        } catch (Exception $e) {
            echo "✗ Migration failed: " . $e->getMessage() . "\n";
            echo "Trace:\n" . $e->getTraceAsString() . "\n";
            exit(1);
        }

        echo "\n";
    }

    private function verify_installation(): void
    {
        echo "=== Verifying Installation ===\n";

        try {
            // Check databases exist
            $databases = ['app', 'articles', 'users', 'forms'];
            foreach ($databases as $db) {
                $dbPath = __DIR__ . "/../data/{$db}.db";
                if (file_exists($dbPath)) {
                    echo "✓ {$db}.db exists\n";
                } else {
                    echo "✗ {$db}.db missing\n";
                }
            }

            // Check FTS table
            $articlesDb = __DIR__ . '/../data/articles.db';
            if (file_exists($articlesDb)) {
                $conn = DatabaseManager::getConnection('articles');
                $result = $conn->fetchAllAssociative("SELECT COUNT(*) as count FROM articles_fts");
                echo "✓ FTS5 table has " . $result[0]['count'] . " articles\n";
            }

            // Test search functionality
            if (file_exists($articlesDb)) {
                $conn = DatabaseManager::getConnection('articles');
                $result = $conn->fetchAllAssociative(
                    "SELECT COUNT(*) as count FROM articles_fts WHERE articles_fts MATCH 'php'"
                );
                echo "✓ Search functionality working (found {$result[0]['count']} PHP articles)\n";
            }

            echo "\n";
        } catch (Exception $e) {
            echo "✗ Verification failed: " . $e->getMessage() . "\n";
        }
    }

    private function create_admin_user(): void
    {
        echo "=== Admin User Setup ===\n";

        if ($this->confirm("Do you want to change the default admin password?")) {
            $password = $this->ask("Enter new admin password:", true);
            
            if (strlen($password) < 8) {
                echo "Password must be at least 8 characters. Using default.\n";
                return;
            }

            try {
                $conn = DatabaseManager::getConnection('users');
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                
                $conn->update('users', 
                    ['password_hash' => $hashed],
                    ['id' => '00000000-0000-0000-0000-000000000001']
                );
                
                echo "✓ Admin password updated\n";
            } catch (Exception $e) {
                echo "⚠ Could not update password: " . $e->getMessage() . "\n";
            }
        }

        echo "\n";
    }

    private function showSummary(): void
    {
        echo "=== Installation Complete ===\n\n";
        
        echo "📦 Application Structure:\n";
        echo "  4 Separate Databases:\n";
        echo "    • data/app.db      - Images, Audit Logs\n";
        echo "    • data/articles.db - Articles, Categories, Tags, FTS5\n";
        echo "    • data/users.db    - User accounts\n";
        echo "    • data/forms.db    - Forms and submissions\n";
        
        echo "\n👤 Default Admin Account:\n";
        echo "   Username: admin\n";
        echo "   Email: admin@example.com\n";
        echo "   Password: admin123 (unless you changed it)\n";
        
        echo "\n🚀 Quick Start:\n";
        echo "  1. Start server: php -S localhost:8000 web.php\n";
        echo "  2. Open browser: http://localhost:8000\n";
        echo "  3. Login with admin credentials\n";
        echo "  4. Configure Cloudinary in .env for image uploads\n";
        
        echo "\n🔧 Useful Commands:\n";
        echo "  Verify:    php scripts/verify_installation.php\n";
        echo "  Migrate:   php migrations/run_migrations.php\n";
        echo "  Reset:     rm -rf data/*.db && php scripts/install_complete.php\n";
        
        echo "\n✅ Installation successful! Your DDD application is ready.\n";
    }

    private function confirm(string $message): bool
    {
        echo "$message (yes/no) [yes]: ";
        $input = strtolower(trim(fgets(STDIN)));
        
        return $input === '' || $input === 'y' || $input === 'yes';
    }

    private function ask(string $message, bool $hidden = false): string
    {
        echo "$message ";
        
        if ($hidden && function_exists('readline_add_history')) {
            system('stty -echo');
            $input = trim(readline());
            system('stty echo');
            echo "\n";
        } else {
            $input = trim(fgets(STDIN));
        }
        
        return $input;
    }
}

// Run installer
$installer = new Installer();
$installer->run();
