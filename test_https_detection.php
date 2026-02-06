<?php

declare(strict_types=1);

// Test script for HTTPS detection
require_once __DIR__ . '/boot.php';

use Blog\Security\HttpsDetector;

// Mock the SessionMiddleware for testing
class TestSessionMiddleware
{
    public function testHttpsDetection()
    {
        echo "=== HTTPS DETECTION SECURITY TEST ===\n\n";

        // Store original server vars
        $originalServer = $_SERVER;

        // Test cases
        $testCases = [
            // Standard HTTPS detection
            [
                'name' => 'Standard HTTPS (on)',
                'server' => ['HTTPS' => 'on'],
                'expected' => true,
                'description' => 'Direct HTTPS connection'
            ],
            [
                'name' => 'Standard HTTPS (1)',
                'server' => ['HTTPS' => '1'],
                'expected' => true,
                'description' => 'Direct HTTPS connection (numeric)'
            ],
            [
                'name' => 'HTTPS disabled (off)',
                'server' => ['HTTPS' => 'off'],
                'expected' => false,
                'description' => 'HTTP connection with HTTPS=off'
            ],
            [
                'name' => 'HTTPS not set',
                'server' => [],
                'expected' => false,
                'description' => 'HTTP connection'
            ],
            
            // Port-based detection
            [
                'name' => 'HTTPS via port 443',
                'server' => ['SERVER_PORT' => '443'],
                'expected' => true,
                'description' => 'HTTPS on standard port'
            ],
            [
                'name' => 'HTTP via port 80',
                'server' => ['SERVER_PORT' => '80'],
                'expected' => false,
                'description' => 'HTTP on standard port'
            ],
            
            // Proxy/LB headers
            [
                'name' => 'X-Forwarded-Proto (https)',
                'server' => ['HTTP_X_FORWARDED_PROTO' => 'https'],
                'expected' => true,
                'description' => 'Behind proxy with X-Forwarded-Proto'
            ],
            [
                'name' => 'X-Forwarded-Proto (http)',
                'server' => ['HTTP_X_FORWARDED_PROTO' => 'http'],
                'expected' => false,
                'description' => 'Behind proxy with HTTP X-Forwarded-Proto'
            ],
            [
                'name' => 'X-Forwarded-SSL (on)',
                'server' => ['HTTP_X_FORWARDED_SSL' => 'on'],
                'expected' => true,
                'description' => 'Behind proxy with X-Forwarded-SSL'
            ],
            [
                'name' => 'CloudFront HTTPS',
                'server' => ['HTTP_CLOUDFRONT_FORWARDED_PROTO' => 'https'],
                'expected' => true,
                'description' => 'Behind AWS CloudFront'
            ],
            
            // Edge cases
            [
                'name' => 'HTTPS=off but proxy says HTTPS',
                'server' => ['HTTPS' => 'off', 'HTTP_X_FORWARDED_PROTO' => 'https'],
                'expected' => true,
                'description' => 'Proxy overrides local HTTPS=off'
            ],
            [
                'name' => 'HTTPS=on but proxy says HTTP',
                'server' => ['HTTPS' => 'on', 'HTTP_X_FORWARDED_PROTO' => 'http'],
                'expected' => true,
                'description' => 'Local HTTPS takes precedence'
            ],
        ];

        foreach ($testCases as $i => $testCase) {
            // Set test environment
            $_SERVER = array_merge($originalServer, $testCase['server']);
            
            // Test detection
            $result = HttpsDetector::isHttps();
            $status = ($result === $testCase['expected']) ? '✅ PASS' : '❌ FAIL';
            
            echo sprintf("Test %d: %s\n", $i + 1, $testCase['name']);
            echo sprintf("  Expected: %s, Got: %s | %s\n", 
                $testCase['expected'] ? 'true' : 'false',
                $result ? 'true' : 'false',
                $status
            );
            echo sprintf("  %s\n\n", $testCase['description']);
        }

        // Restore original server vars
        $_SERVER = $originalServer;

        echo "=== SESSION FACTORY INTEGRATION TEST ===\n";
        
        // Test that session config gets proper HTTPS detection
        $config = require __DIR__ . '/config/session.php';
        
        // Simulate HTTPS environment
        $_SERVER = ['HTTPS' => 'on'];
        $httpsDetected = HttpsDetector::isHttps();
        
        if (isset($config['security']['cookie_secure']) && $config['security']['cookie_secure'] === 'auto') {
            $config['security']['cookie_secure'] = HttpsDetector::isHttps();
            echo "Session config override: ✅ PASS\n";
            echo "HTTPS detected: {$httpsDetected}\n";
            echo "cookie_secure set to: " . ($config['security']['cookie_secure'] ? 'true' : 'false') . "\n";
        } else {
            echo "Session config override: ❌ FAIL - auto setting not found\n";
        }

        echo "\n=== TEST COMPLETE ===\n";
    }
}

// Run the test
$tester = new TestSessionMiddleware();
$tester->testHttpsDetection();
