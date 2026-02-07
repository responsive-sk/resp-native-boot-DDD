<?php

declare(strict_types=1);

namespace Blog\Security;

use Blog\Security\Exception\AuthenticationException;
use ResponsiveSk\Slim4Session\SessionInterface;

final class SessionManager
{
    private const string USER_ID_KEY = 'user_id';
    private const string USER_ROLE_KEY = 'user_role';
    private const string CSRF_TOKEN_KEY = 'csrf_token';
    private const string LAST_ACTIVITY_KEY = 'last_activity';
    private const string USER_AGENT_HASH_KEY = 'user_agent_hash';
    private const string CREATED_AT_KEY = 'created_at';

    public function __construct(
        private readonly SessionInterface $session,
        private readonly int $lifetime = 3600,
        private readonly int $timeout = 1800,
        private readonly string $binding = 'user_agent',
        private readonly ?SecurityLogger $logger = null
    ) {
    }

    public function startSession(string $userId, string $userRole): void
    {
        $now = time();

        $this->session->set(self::USER_ID_KEY, $userId);
        $this->session->set(self::USER_ROLE_KEY, $userRole);
        $this->session->set(self::CREATED_AT_KEY, $now);
        $this->session->set(self::LAST_ACTIVITY_KEY, $now);

        if ($this->binding === 'user_agent') {
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $this->session->set(self::USER_AGENT_HASH_KEY, hash('sha256', $userAgent));
        }
    }

    public function isValid(): bool
    {
        if (!$this->session->has(self::USER_ID_KEY) || !$this->session->has(self::USER_ROLE_KEY)) {
            return false;
        }

        $userId = $this->session->get(self::USER_ID_KEY);
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        if ($this->binding === 'user_agent' && $this->session->has(self::USER_AGENT_HASH_KEY)) {
            $currentUserAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $expectedHash = $this->session->get(self::USER_AGENT_HASH_KEY);

            if (!hash_equals($expectedHash, hash('sha256', $currentUserAgent))) {
                $this->logger?->logSessionHijackingDetected($userId, $ip);

                return false;
            }
        }

        $now = time();

        if ($this->session->has(self::CREATED_AT_KEY)) {
            $createdAt = $this->session->get(self::CREATED_AT_KEY);
            if ($now - $createdAt > $this->lifetime) {
                $lastActivity = date('Y-m-d H:i:s', $this->session->get(self::LAST_ACTIVITY_KEY, $createdAt));
                $this->logger?->logSessionTimeout($userId, $lastActivity);

                return false;
            }
        }

        if ($this->session->has(self::LAST_ACTIVITY_KEY)) {
            $lastActivity = $this->session->get(self::LAST_ACTIVITY_KEY);
            if ($now - $lastActivity > $this->timeout) {
                $lastActivityFormatted = date('Y-m-d H:i:s', $lastActivity);
                $this->logger?->logSessionTimeout($userId, $lastActivityFormatted);

                return false;
            }
        }

        return true;
    }

    public function refreshActivity(): void
    {
        if ($this->isValid()) {
            $this->session->set(self::LAST_ACTIVITY_KEY, time());
        }
    }

    public function destroy(): void
    {
        $this->session->clear();
    }

    public function getUserId(): ?string
    {
        return $this->session->get(self::USER_ID_KEY);
    }

    public function getUserRole(): ?string
    {
        return $this->session->get(self::USER_ROLE_KEY);
    }

    public function getSessionData(): array
    {
        return [
            'user_id' => $this->session->get(self::USER_ID_KEY),
            'user_role' => $this->session->get(self::USER_ROLE_KEY),
            'csrf_token' => $this->session->get(self::CSRF_TOKEN_KEY),
            'last_activity' => $this->session->get(self::LAST_ACTIVITY_KEY),
            'user_agent_hash' => $this->session->get(self::USER_AGENT_HASH_KEY),
            'created_at' => $this->session->get(self::CREATED_AT_KEY),
        ];
    }

    public function requireValidSession(): void
    {
        if (!$this->isValid()) {
            $this->destroy();

            throw AuthenticationException::notAuthenticated();
        }
    }
}
