<?php
// View helper functions
// Placeholder file - add your view helpers here

if (!function_exists('asset')) {
    function asset(string $path): string {
        return '/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string {
        return '/' . ltrim($path, '/');
    }
}
