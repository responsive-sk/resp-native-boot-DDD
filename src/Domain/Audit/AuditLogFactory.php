<?php

declare(strict_types=1);

namespace Blog\Domain\Audit;

use Blog\Domain\Audit\Entity\AuditLog;
use Blog\Domain\Audit\ValueObject\AuditEventType;

class AuditLogFactory
{
    public static function createLoginSuccess(
        string $email,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): AuditLog {
        return AuditLog::createAuthenticationEvent(
            $email,
            new AuditEventType(AuditEventType::LOGIN_SUCCESS),
            true,
            $ipAddress,
            $userAgent
        );
    }

    public static function createLoginFailed(
        string $email,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): AuditLog {
        return AuditLog::createAuthenticationEvent(
            $email,
            new AuditEventType(AuditEventType::LOGIN_FAILED),
            false,
            $ipAddress,
            $userAgent
        );
    }

    public static function createAuthorizationDenied(
        ?string $userId,
        string $resource,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): AuditLog {
        return AuditLog::createAuthorizationEvent(
            $userId,
            new AuditEventType(AuditEventType::AUTHORIZATION_DENIED),
            $resource,
            false,
            $ipAddress,
            $userAgent
        );
    }

    public static function createArticleCreated(
        string $articleId,
        ?string $userId,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): AuditLog {
        return AuditLog::createArticleEvent(
            new AuditEventType(AuditEventType::ARTICLE_CREATED),
            $articleId,
            $userId,
            $ipAddress,
            $userAgent
        );
    }

    public static function createImageUploaded(
        string $imageId,
        ?string $userId,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): AuditLog {
        return AuditLog::create(
            new AuditEventType(AuditEventType::IMAGE_UPLOADED),
            $userId,
            $ipAddress,
            $userAgent,
            ['image_id' => $imageId]
        );
    }
}