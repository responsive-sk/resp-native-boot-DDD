<?php

declare(strict_types=1);

namespace Blog\Security;

/**
 * Utility class for reliable HTTPS detection
 * Handles various proxy/load balancer scenarios
 */
final class HttpsDetector
{
    /**
     * Properly detect if the request is using HTTPS
     * Handles various proxy/load balancer scenarios
     */
    public static function isHttps(): bool
    {
        // Check standard HTTPS server variable
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            return true;
        }

        // Check for HTTPS on standard port
        if (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443) {
            return true;
        }

        // Check X-Forwarded-Proto header (common with load balancers/proxies)
        if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])
            && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
            return true;
        }

        // Check X-Forwarded-SSL header (some proxies use this)
        if (!empty($_SERVER['HTTP_X_FORWARDED_SSL'])
            && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) === 'on') {
            return true;
        }

        // Check CloudFront header
        if (!empty($_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO'])
            && strtolower($_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO']) === 'https') {
            return true;
        }

        // Check for other common proxy headers
        if (!empty($_SERVER['HTTP_X_FORWARDED_SCHEME'])
            && strtolower($_SERVER['HTTP_X_FORWARDED_SCHEME']) === 'https') {
            return true;
        }

        // Check for Front-End-Https header (IIS)
        if (!empty($_SERVER['HTTP_FRONT_END_HTTPS'])
            && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) === 'on') {
            return true;
        }

        return false;
    }

    /**
     * Get the current protocol (http or https)
     */
    public static function getProtocol(): string
    {
        return self::isHttps() ? 'https' : 'http';
    }

    /**
     * Get the current base URL with proper protocol
     */
    public static function getBaseUrl(): string
    {
        $protocol = self::getProtocol();
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        return $protocol . '://' . $host;
    }
}
