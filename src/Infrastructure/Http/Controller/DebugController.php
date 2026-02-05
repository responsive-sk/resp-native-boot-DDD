<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class DebugController
{
    public function error(ServerRequestInterface $request): ResponseInterface
    {
        // Get all available error information
        $error = error_get_last();
        $errorLog = ini_get('error_log');
        $displayErrors = ini_get('display_errors');
        $errorReporting = error_reporting();
        
        // Get last few lines from error log
        $logContent = '';
        if (file_exists($errorLog)) {
            $lines = file($errorLog);
            $logContent = implode("\n", array_slice($lines, -20)); // Last 20 lines
        }
        
        // Get exception from request if any
        $exception = $request->getAttribute('exception');
        $stackTrace = '';
        
        if ($exception instanceof Throwable) {
            $stackTrace = $exception->getTraceAsString();
        }
        
        // Get server parameters from request
        $serverParams = $request->getServerParams();
        $cookies = $request->getCookieParams();
        $post = $request->getParsedBody();
        $get = $request->getQueryParams();
        
        // Build debug information
        $debugInfo = [
            'timestamp' => date('Y-m-d H:i:s'),
            'php_version' => PHP_VERSION,
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true),
            'error_reporting' => $errorReporting,
            'display_errors' => $displayErrors,
            'last_error' => $error,
            'error_log_file' => $errorLog,
            'server_vars' => [
                'REQUEST_URI' => $serverParams['REQUEST_URI'] ?? 'N/A',
                'REQUEST_METHOD' => $serverParams['REQUEST_METHOD'] ?? 'N/A',
                'HTTP_HOST' => $serverParams['HTTP_HOST'] ?? 'N/A',
                'SCRIPT_NAME' => $serverParams['SCRIPT_NAME'] ?? 'N/A',
            ],
            'session' => $this->getSessionInfo(),
            'cookies' => $cookies,
            'post_data' => is_array($post) ? $post : [],
            'get_data' => $get,
            'headers' => $this->getAllHeaders($serverParams),
        ];
        
        // Build HTML response
        $html = $this->buildDebugHtml($debugInfo, $logContent, $stackTrace, $exception);
        
        // Create response
        $response = new \Laminas\Diactoros\Response\HtmlResponse($html);
        return $response->withStatus(500);
    }
    
    private function getSessionInfo(): array
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return [
                'status' => 'active',
                'id' => session_id(),
                'data' => $_SESSION,
            ];
        }
        
        return [
            'status' => 'inactive',
            'id' => null,
            'data' => [],
        ];
    }
    
    private function getAllHeaders(array $serverParams): array
    {
        $headers = [];
        foreach ($serverParams as $name => $value) {
            if (strpos($name, 'HTTP_') === 0) {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
    
    private function buildDebugHtml(array $debugInfo, string $logContent, string $stackTrace, ?Throwable $exception): string
    {
        $html = '<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Error Page</title>
    <style>
        body { font-family: monospace; margin: 0; padding: 20px; background: #1a1a1a; color: #fff; }
        .container { max-width: 1200px; margin: 0 auto; }
        .section { margin-bottom: 30px; border: 1px solid #333; border-radius: 5px; overflow: hidden; }
        .section h2 { margin: 0; padding: 15px; background: #333; color: #fff; }
        .section-content { padding: 15px; }
        .error { color: #ff6b6b; background: #2d1b1b; padding: 10px; border-radius: 3px; margin: 10px 0; }
        .success { color: #51cf66; background: #1b2d1b; padding: 10px; border-radius: 3px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 8px; border: 1px solid #444; text-align: left; vertical-align: top; }
        th { background: #333; font-weight: bold; }
        pre { background: #2d2d2d; padding: 10px; border-radius: 3px; overflow-x: auto; white-space: pre-wrap; }
        .stack-trace { background: #2d1b1b; color: #ff6b6b; }
        .log-content { background: #1b2d1b; max-height: 300px; overflow-y: auto; }
        .toggle { cursor: pointer; background: #333; padding: 5px 10px; margin: 5px 0; border-radius: 3px; }
        .toggle:hover { background: #444; }
        .hidden { display: none; }
        .visible { display: block; }
    </style>
    <script>
        function toggleSection(id) {
            const element = document.getElementById(id);
            const toggle = document.getElementById(\'toggle-\' + id);
            if (element.classList.contains(\'hidden\')) {
                element.classList.remove(\'hidden\');
                element.classList.add(\'visible\');
                toggle.textContent = \'Skryť ▲\';
            } else {
                element.classList.remove(\'visible\');
                element.classList.add(\'hidden\');
                toggle.textContent = \'Zobraziť ▼\';
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>🐛 Debug Error Page</h1>
        
        <div class="section">
            <h2>📊 Basic Information</h2>
            <div class="section-content">
                <table>
                    <tr><th>Timestamp</th><td>' . htmlspecialchars($debugInfo['timestamp']) . '</td></tr>
                    <tr><th>PHP Version</th><td>' . htmlspecialchars($debugInfo['php_version']) . '</td></tr>
                    <tr><th>Memory Usage</th><td>' . $this->formatBytes($debugInfo['memory_usage']) . '</td></tr>
                    <tr><th>Memory Peak</th><td>' . $this->formatBytes($debugInfo['memory_peak']) . '</td></tr>
                    <tr><th>Error Reporting</th><td>' . $this->errorReportingToString($debugInfo['error_reporting']) . '</td></tr>
                    <tr><th>Display Errors</th><td>' . ($debugInfo['display_errors'] ? 'ON' : 'OFF') . '</td></tr>
                </table>
            </div>
        </div>';
        
        if ($exception) {
            $html .= '
        <div class="section">
            <h2>🚨 Exception Details</h2>
            <div class="section-content">
                <div class="error">
                    <strong>Exception:</strong> ' . get_class($exception) . '<br>
                    <strong>Message:</strong> ' . htmlspecialchars($exception->getMessage()) . '<br>
                    <strong>File:</strong> ' . htmlspecialchars($exception->getFile()) . ':' . $exception->getLine() . '<br>
                    <strong>Code:</strong> ' . $exception->getCode() . '
                </div>';
            
            if ($stackTrace) {
                $html .= '
                <div class="toggle" id="toggle-stack" onclick="toggleSection(\'stack\')">Zobraziť Stack Trace ▼</div>
                <div id="stack" class="hidden">
                    <pre class="stack-trace">' . htmlspecialchars($stackTrace) . '</pre>
                </div>';
            }
            
            $html .= '
            </div>
        </div>';
        }
        
        if ($debugInfo['last_error']) {
            $html .= '
        <div class="section">
            <h2>⚠️ Last PHP Error</h2>
            <div class="section-content">
                <div class="error">
                    <strong>Type:</strong> ' . htmlspecialchars($debugInfo['last_error']['type'] ?? 'Unknown') . '<br>
                    <strong>Message:</strong> ' . htmlspecialchars($debugInfo['last_error']['message'] ?? 'No message') . '<br>
                    <strong>File:</strong> ' . htmlspecialchars($debugInfo['last_error']['file'] ?? 'Unknown') . ':' . ($debugInfo['last_error']['line'] ?? '?') . '<br>
                    <strong>Context:</strong> <pre>' . htmlspecialchars(print_r($debugInfo['last_error']['context'] ?? [], true)) . '</pre>
                </div>
            </div>
        </div>';
        }
        
        $html .= '
        <div class="section">
            <h2>🌐 Request Information</h2>
            <div class="section-content">
                <table>
                    <tr><th>Request URI</th><td>' . htmlspecialchars($debugInfo['server_vars']['REQUEST_URI']) . '</td></tr>
                    <tr><th>Request Method</th><td>' . htmlspecialchars($debugInfo['server_vars']['REQUEST_METHOD']) . '</td></tr>
                    <tr><th>HTTP Host</th><td>' . htmlspecialchars($debugInfo['server_vars']['HTTP_HOST']) . '</td></tr>
                    <tr><th>Script Name</th><td>' . htmlspecialchars($debugInfo['server_vars']['SCRIPT_NAME']) . '</td></tr>
                </table>
            </div>
        </div>';
        
        if (!empty($debugInfo['headers'])) {
            $html .= '
        <div class="section">
            <h2>📤 Request Headers</h2>
            <div class="section-content">
                <table>';
            foreach ($debugInfo['headers'] as $name => $value) {
                $html .= '<tr><th>' . htmlspecialchars($name) . '</th><td>' . htmlspecialchars($value) . '</td></tr>';
            }
            $html .= '
                </table>
            </div>
        </div>';
        }
        
        $html .= '
        <div class="section">
            <h2>🍪 Session Information</h2>
            <div class="section-content">
                <table>
                    <tr><th>Status</th><td>' . htmlspecialchars($debugInfo['session']['status']) . '</td></tr>
                    <tr><th>Session ID</th><td>' . htmlspecialchars($debugInfo['session']['id'] ?? 'None') . '</td></tr>
                </table>';
            if (!empty($debugInfo['session']['data'])) {
                $html .= '<h4>Session Data:</h4><pre>' . htmlspecialchars(print_r($debugInfo['session']['data'], true)) . '</pre>';
            }
            $html .= '
            </div>
        </div>';
        
        if (!empty($debugInfo['post_data'])) {
            $html .= '
        <div class="section">
            <h2>📝 POST Data</h2>
            <div class="section-content">
                <pre>' . htmlspecialchars(print_r($debugInfo['post_data'], true)) . '</pre>
            </div>
        </div>';
        }
        
        if (!empty($debugInfo['get_data'])) {
            $html .= '
        <div class="section">
            <h2>🔍 GET Data</h2>
            <div class="section-content">
                <pre>' . htmlspecialchars(print_r($debugInfo['get_data'], true)) . '</pre>
            </div>
        </div>';
        }
        
        if (!empty($debugInfo['cookies'])) {
            $html .= '
        <div class="section">
            <h2>🍪 Cookies</h2>
            <div class="section-content">
                <pre>' . htmlspecialchars(print_r($debugInfo['cookies'], true)) . '</pre>
            </div>
        </div>';
        }
        
        if ($logContent) {
            $html .= '
        <div class="section">
            <h2>📋 Error Log (Last 20 lines)</h2>
            <div class="section-content">
                <div class="log-content">
                    <pre>' . htmlspecialchars($logContent) . '</pre>
                </div>
            </div>
        </div>';
        }
        
        $html .= '
    </div>
</body>
</html>';
        
        return $html;
    }
    
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
    
    private function errorReportingToString(int $level): string
    {
        $levels = [
            E_ERROR => 'E_ERROR',
            E_WARNING => 'E_WARNING',
            E_PARSE => 'E_PARSE',
            E_NOTICE => 'E_NOTICE',
            E_CORE_ERROR => 'E_CORE_ERROR',
            E_CORE_WARNING => 'E_CORE_WARNING',
            E_COMPILE_ERROR => 'E_COMPILE_ERROR',
            E_COMPILE_WARNING => 'E_COMPILE_WARNING',
            E_USER_ERROR => 'E_USER_ERROR',
            E_USER_WARNING => 'E_USER_WARNING',
            E_USER_NOTICE => 'E_USER_NOTICE',
            E_STRICT => 'E_STRICT',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            E_DEPRECATED => 'E_DEPRECATED',
            E_USER_DEPRECATED => 'E_USER_DEPRECATED',
        ];
        
        $result = [];
        foreach ($levels as $levelNum => $levelName) {
            if ($level & $levelNum) {
                $result[] = $levelName;
            }
        }
        
        return implode(' | ', $result);
    }
}
