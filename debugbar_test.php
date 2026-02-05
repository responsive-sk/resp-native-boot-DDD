<?php

declare(strict_types=1);

// Test DebugBar CSS injection
require_once __DIR__ . '/boot.php';

// Load DebugBar styles
use Blog\Infrastructure\DebugBar\BlogDebugBarStyles;

$css = BlogDebugBarStyles::getCustomCss();

echo "<!DOCTYPE html>\n";
echo "<html>\n";
echo "<head>\n";
echo "<title>DebugBar CSS Test</title>\n";
echo "<style>\n";
echo $css;
echo "</style>\n";
echo "</head>\n";
echo "<body>\n";
echo "<h1>DebugBar CSS Test</h1>\n";
echo "<p>Toto je test, či sa CSS správne načíta.</p>\n";

// Test DebugBar logo button
echo "<a class='phpdebugbar-restore-btn' href='#'>Test Logo Button</a>\n";

// Test header styling
echo "<div class='phpdebugbar-header'>\n";
echo "<h2>DebugBar Header Test</h2>\n";
echo "</div>\n";

// Test tab styling
echo "<div style='margin: 20px 0;'>\n";
echo "<a class='phpdebugbar-tab phpdebugbar-active'>Active Tab Test</a>\n";
echo "<a class='phpdebugbar-tab'>Normal Tab Test</a>\n";
echo "</div>\n";

// Test badge styling
echo "<div style='margin: 20px 0;'>\n";
echo "<a class='phpdebugbar-tab'>Tab with <span class='phpdebugbar-badge phpdebugbar-important'>Important Badge</span></a>\n";
echo "</div>\n";

echo "</body>\n";
echo "</html>\n";
