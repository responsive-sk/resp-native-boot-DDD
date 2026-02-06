<?php

declare(strict_types=1);

// Test script for open redirect vulnerability
require_once __DIR__ . '/boot.php';

use Blog\Infrastructure\Http\Controller\BaseController;

// Mock controller for testing
class TestController extends BaseController
{
    public function __construct()
    {
        // Mock dependencies
    }

    public function testRedirects()
    {
        echo "=== REDIRECT SECURITY TEST ===\n\n";

        // Test cases
        $testCases = [
            // Safe redirects
            '/dashboard' => 'SAFE - Relative URL',
            '/mark/articles' => 'SAFE - Relative URL',
            'http://localhost/dashboard' => 'SAFE - Same origin',
            'https://localhost/dashboard' => 'SAFE - Same origin HTTPS',
            
            // Dangerous redirects (should be blocked)
            'https://evil.com' => 'DANGEROUS - External domain',
            'http://evil.com' => 'DANGEROUS - External domain',
            '//evil.com' => 'DANGEROUS - Protocol-relative',
            'javascript:alert(1)' => 'DANGEROUS - JavaScript protocol',
            'data:text/html,<script>alert(1)</script>' => 'DANGEROUS - Data protocol',
            'ftp://evil.com' => 'DANGEROUS - FTP protocol',
        ];

        foreach ($testCases as $url => $description) {
            $result = $this->getSafeRedirect(new \Nyholm\Psr7\ServerRequest('GET', '/login?redirect=' . urlencode($url)), '/home');
            
            $status = ($result === $url) ? '✅ ALLOWED' : '🚫 BLOCKED';
            $finalUrl = ($result === $url) ? $url : $result;
            
            echo sprintf("%-50s | %-10s | %s\n", 
                substr($url, 0, 50), 
                $status, 
                $description
            );
            echo sprintf("    Final URL: %s\n\n", $finalUrl);
        }

        echo "=== TEST COMPLETE ===\n";
    }
}

// Run the test
$controller = new TestController();
$controller->testRedirects();
