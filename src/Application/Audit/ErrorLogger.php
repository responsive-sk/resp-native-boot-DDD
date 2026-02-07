<?php

declare(strict_types=1);

namespace Blog\Application\Audit;

use Throwable;

final readonly class ErrorLogger
{
    private string $logFilePath;

    public function __construct(string $logFilePath)
    {
        $this->logFilePath = $logFilePath;
        $this->ensureLogFileExists();
    }

    private function ensureLogFileExists(): void
    {
        $dir = dirname($this->logFilePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        if (!file_exists($this->logFilePath)) {
            file_put_contents($this->logFilePath, '');
        }
    }

    public function log(Throwable $e): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = sprintf(
            "[%s] ERROR: %s in %s on line %d
Stack Trace:
%s

",
            $timestamp,
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );

        error_log($logMessage, 3, $this->logFilePath);
    }
}
