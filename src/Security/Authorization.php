<?php

declare(strict_types=1);

namespace Blog\Security;

use Blog\Security\Exception\AuthenticationException;
use Blog\Security\Exception\AuthorizationException;
use ResponsiveSk\Slim4Session\SessionInterface;

class Authorization
{
    private static ?SessionInterface $session = null;
    
    public static function setSession(SessionInterface $session): void
    {
        self::$session = $session;
    }
    
    private static function getSession(): SessionInterface
    {
        if (self::$session === null) {
            // Fallback to direct session if not set
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            // Create a simple wrapper
            self::$session = new class implements SessionInterface {
                public function get(string $key, mixed $default = null): mixed {
                    return $_SESSION[$key] ?? $default;
                }
                public function set(string $key, mixed $value): void {
                    $_SESSION[$key] = $value;
                }
                public function has(string $key): bool {
                    return isset($_SESSION[$key]);
                }
                public function delete(string $key): void {
                    unset($_SESSION[$key]);
                }
                public function clear(): void {
                    $_SESSION = [];
                }
                public function all(): array {
                    return $_SESSION;
                }
                public function regenerate(): void {
                    session_regenerate_id(false);
                }
                public function destroy(): bool {
                    return session_destroy();
                }
                public function getId(): string {
                    return session_id();
                }
                public function getName(): string {
                    return session_name();
                }
                public function save(): void {
                    session_write_close();
                }
                public function isStarted(): bool {
                    return session_status() === PHP_SESSION_ACTIVE;
                }
                public function migrate(bool $destroy = false): bool {
                    return session_regenerate_id($destroy);
                }
                public function invalidate(): bool {
                    return session_destroy();
                }
                public function remove(string $key): void {
                    unset($_SESSION[$key]);
                }
                public function start(): bool {
                    if (session_status() === PHP_SESSION_NONE) {
                        return session_start();
                    }
                    return true;
                }
                public function regenerateId(bool $deleteOldSession = false): bool {
                    return session_regenerate_id($deleteOldSession);
                }
            };
        }
        
        return self::$session;
    }

    public static function isAuthenticated(): bool
    {
        $session = self::getSession();
        
        return $session->has('user_id') && $session->has('user_role');
    }

    public static function getUser(): ?array
    {
        $session = self::getSession();
        
        if (!$session->has('user_id') || !$session->has('user_role')) {
            return null;
        }

        return [
            'id' => $session->get('user_id'),
            'role' => $session->get('user_role'),
        ];
    }

    public static function hasRole(string $role): bool
    {
        $user = self::getUser();

        if ($user === null) {
            return false;
        }

        // Normalize both roles for comparison
        $normalizeRole = function(string $role): string {
            $role = strtolower(trim($role));
            // Remove ROLE_ prefix if present
            if (str_starts_with($role, 'role_')) {
                $role = substr($role, 5);
            }
            return $role;
        };

        return $normalizeRole($user['role']) === $normalizeRole($role);
    }

    public static function isMark(): bool
    {
        $session = self::getSession();
        
        // Check mark session flag first
        if ($session->has('mark_session') && $session->get('mark_session') === true) {
            return true;
        }
        
        // Fallback to role check
        return self::hasRole('mark') || self::hasRole('ROLE_MARK');
    }

    public static function requireAuth(): void
    {
        if (!self::isAuthenticated()) {
            throw AuthenticationException::notAuthenticated();
        }
    }

    public static function requireRole(string $role): void
    {
        self::requireAuth();

        if (!self::hasRole($role)) {
            throw AuthorizationException::notAuthorized($role);
        }
    }

    public static function requireMark(): void
    {
        self::requireAuth();
        
        if (!self::isMark()) {
            throw AuthorizationException::notAuthorized('MARK role required');
        }
    }
}
