<?php

declare(strict_types=1);

echo "=== Audit Code Quality Check ===\n\n";

// Check for hardcoded event type strings
$patterns = [
    'login_success',
    'login_failed', 
    'authorization_denied',
    'registration',
    'logout',
    'article_created',
    'article_updated',
    'article_deleted',
    'user_created',
    'image_uploaded'
];

$excludePatterns = [
    'AuditEventType::',
    'const ',
    '// ',
    '# ',
    '/* ',
    '* ',
    '"auth.logout"',
    '"registration logic"',
    'public function logout',
    'class.*Controller',
    'class.*Middleware'
];

$issues = [];

foreach ($patterns as $pattern) {
    $command = "grep -r \"$pattern\" /home/evan/Desktop/02/ag/resp-blog/src/ --include=\"*.php\"";
    $output = shell_exec($command);
    
    if ($output) {
        $lines = explode("\n", trim($output));
        foreach ($lines as $line) {
            $shouldExclude = false;
            foreach ($excludePatterns as $exclude) {
                if (strpos($line, $exclude) !== false) {
                    $shouldExclude = true;
                    break;
                }
            }
            
            if (!$shouldExclude && !empty(trim($line))) {
                $issues[] = $line;
            }
        }
    }
}

if (empty($issues)) {
    echo "✅ No hardcoded event type strings found!\n";
    echo "✅ All code properly uses AuditEventType constants.\n";
} else {
    echo "⚠️  Found potential issues:\n";
    foreach ($issues as $issue) {
        echo "   - $issue\n";
    }
    echo "\n❌ Please replace hardcoded strings with AuditEventType constants.\n";
}

// Check for proper imports
echo "\n=== Import Check ===\n";
$importCommand = "grep -r \"use.*AuditEventType\" /home/evan/Desktop/02/ag/resp-blog/src/ --include=\"*.php\"";
$imports = shell_exec($importCommand);

$importFiles = [];
if ($imports) {
    $lines = explode("\n", trim($imports));
    foreach ($lines as $line) {
        if (!empty(trim($line))) {
            $importFiles[] = $line;
        }
    }
}

echo "Files with AuditEventType imports: " . count($importFiles) . "\n";
foreach ($importFiles as $import) {
    echo "✓ $import\n";
}

echo "\n🎉 Audit code quality check completed!\n";