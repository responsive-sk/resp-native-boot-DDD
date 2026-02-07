<?php

declare(strict_types=1);

namespace Blog\Security;

use Blog\Security\Exception\AuthenticationException;
use Blog\Security\Exception\AuthorizationException;
use ResponsiveSk\Slim4Session\SessionInterface;

final class AuthorizationService
{
    public function __construct(
        private readonly SessionInterface $session
    ) {
    }

    public function isAuthenticated(): bool
    {
        return $this->session->has('user_id')
            && $this->session->has('user_role');
    }

    public function getUser(): ?array
    {
        if (!$this->session->has('user_id') || !$this->session->has('user_role')) {
            return null;
        }

        return [
            'id' => $this->session->get('user_id'),
            'role' => $this->session->get('user_role'),
        ];
    }

    public function hasRole(string $role): bool
    {
        $user = $this->getUser();

        if ($user === null) {
            return false;
        }

        // Normalize both roles for comparison
        $normalizeRole = function (string $role): string {
            $role = strtolower(trim($role));
            // Remove ROLE_ prefix if present
            if (str_starts_with($role, 'role_')) {
                $role = substr($role, 5);
            }

            return $role;
        };

        return $normalizeRole($user['role']) === $normalizeRole($role);
    }

    public function isMark(): bool
    {
        // Check mark session flag first
        if ($this->session->has('mark_session') && $this->session->get('mark_session') === true) {
            return true;
        }

        // Fallback to role check
        return $this->hasRole('mark') || $this->hasRole('ROLE_MARK');
    }

    public function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            throw AuthenticationException::notAuthenticated();
        }
    }

    public function requireRole(string $role): void
    {
        $this->requireAuth();

        if (!$this->hasRole($role)) {
            throw AuthorizationException::notAuthorized($role);
        }
    }

    public function requireMark(): void
    {
        $this->requireAuth();

        if (!$this->isMark()) {
            throw AuthorizationException::notAuthorized('MARK role required');
        }
    }

    public function setMarkSession(bool $isMark): void
    {
        $this->session->set('mark_session', $isMark);
    }

    public function clearMarkSession(): void
    {
        $this->session->delete('mark_session');
    }
}
