<?php

declare(strict_types=1);

namespace Blog\Security;

class SecurityLogger
{
    private string $logPath;

    public function __construct(string $logPath)
    {
        $this->logPath = $logPath;
    }

    public function logAuthenticationFailure(?string $userId, string $ip): void
    {
        $this->log('AUTHENTICATION_FAILURE', "Invalid credentials for user_id={$userId}, ip={$ip}");
    }

    public function logSessionTimeout(string $userId, string $lastActivity): void
    {
        $this->log('SESSION_TIMEOUT', "Session expired for user_id={$userId}, last_activity={$lastActivity}");
    }

    public function logCsrfValidationFailure(?string $userId, string $ip, string $endpoint): void
    {
        $this->log('CSRF_VALIDATION_FAILURE', "Invalid token for user_id={$userId}, ip={$ip}, endpoint={$endpoint}");
    }

    public function logSessionHijackingDetected(string $userId, string $ip): void
    {
        $this->log('SESSION_HIJACKING_DETECTED', "User-Agent mismatch for user_id={$userId}, ip={$ip}");
    }

    private function log(string $eventType, string $message): void
    {
        $entry = sprintf(
            "[%s] %s: %s\n",
            date('Y-m-d H:i:s'),
            $eventType,
            $message
        );

        file_put_contents($this->logPath, $entry, FILE_APPEND | LOCK_EX);
    }
}
