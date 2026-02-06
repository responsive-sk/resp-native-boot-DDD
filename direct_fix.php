<?php
// Priamo fixni services_ddd.php
$file = 'config/services_ddd.php';
$content = file_get_contents($file);

// Nájdeme 'database' službu
if (preg_match("/'database'\s*=>\s*[^,]+/", $content, $matches)) {
    echo "Found database service: " . $matches[0] . "\n";
    
    // Nahraď s articles DB
    $fixed = preg_replace(
        "/'database'\s*=>\s*[^,]+/",
        "'database' => fn() => \\Blog\\Database\\DatabaseManager::getConnection('articles')",
        $content
    );
    
    file_put_contents($file, $fixed);
    echo "✅ Fixed database service to use articles DB\n";
} else {
    // Pridaj ak neexistuje
    $fixed = preg_replace(
        "/(Database::class => fn\(\) => DatabaseManager::getConnection\(\),)/",
        "$1\n    'database' => fn() => \\Blog\\Database\\DatabaseManager::getConnection('articles'),",
        $content
    );
    
    file_put_contents($file, $fixed);
    echo "✅ Added missing database service\n";
}

// Verify
echo "\n=== VERIFICATION ===\n";
$containerFactory = require 'config/container.php';
$container = $containerFactory();

if ($container->has('database')) {
    $db = $container->get('database');
    echo "✅ Database service exists\n";
    echo "Path: " . $db->getDatabase() . "\n";
} else {
    echo "❌ Still missing!\n";
}
