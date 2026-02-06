<?php

declare(strict_types=1);

namespace Blog\Security;

use Blog\Security\Exception\AuthenticationException;
use Blog\Security\Exception\AuthorizationException;
use ResponsiveSk\Slim4Session\SessionInterface;
use Psr\Container\ContainerInterface;

/**
 * @deprecated Use AuthorizationService instead. This class maintains backward compatibility.
 */
class Authorization
{
    private static ?AuthorizationService $service = null;
    private static ?ContainerInterface $container = null;

    /**
     * Set the container for service resolution
     */
    public static function setContainer(ContainerInterface $container): void
    {
        self::$container = $container;
    }

    /**
     * Get the authorization service from container
     */
    private static function getService(): AuthorizationService
    {
        if (self::$service === null) {
            if (self::$container === null) {
                throw new \RuntimeException('Authorization container not set. Call Authorization::setContainer() first.');
            }
            
            self::$service = self::$container->get(AuthorizationService::class);
        }
        
        return self::$service;
    }

    /**
     * @deprecated Use AuthorizationService::setSession() instead
     */
    public static function setSession(SessionInterface $session): void
    {
        // This method is deprecated but kept for backward compatibility
        // The session should be injected via DI container
    }

    public static function isAuthenticated(): bool
    {
        return self::getService()->isAuthenticated();
    }

    public static function getUser(): ?array
    {
        return self::getService()->getUser();
    }

    public static function hasRole(string $role): bool
    {
        return self::getService()->hasRole($role);
    }

    public static function isMark(): bool
    {
        return self::getService()->isMark();
    }

    public static function requireAuth(): void
    {
        self::getService()->requireAuth();
    }

    public static function requireRole(string $role): void
    {
        self::getService()->requireRole($role);
    }

    public static function requireMark(): void
    {
        self::getService()->requireMark();
    }
}
