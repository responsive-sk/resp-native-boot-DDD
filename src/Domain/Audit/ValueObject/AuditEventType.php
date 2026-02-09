<?php
// src/Domain/Audit/ValueObject/AuditEventType.php
declare(strict_types=1);

namespace Blog\Domain\Audit\ValueObject;

enum AuditEventType: string
{
    case USER_LOGIN = 'user.login';
    case USER_LOGOUT = 'user.logout';
    case USER_REGISTER = 'user.register';
    case USER_UPDATE = 'user.update';
    case USER_DELETE = 'user.delete';
    case LOGIN_SUCCESS = 'login.success'; // Added for compatibility with AuditLogger.php usage
    case LOGIN_FAILED = 'login.failed'; // Added for compatibility
    case REGISTRATION = 'registration'; // Added for compatibility
    case LOGOUT = 'logout'; // Added for compatibility
    case AUTHORIZATION_DENIED = 'auth.denied'; // Added for compatibility

    case ARTICLE_CREATE = 'article.create';
    case ARTICLE_UPDATE = 'article.update';
    case ARTICLE_DELETE = 'article.delete';
    case ARTICLE_PUBLISH = 'article.publish';

    case CATEGORY_CREATE = 'category.create';
    case CATEGORY_UPDATE = 'category.update';
    case CATEGORY_DELETE = 'category.delete';

    case TAG_CREATE = 'tag.create';
    case TAG_UPDATE = 'tag.update';
    case TAG_DELETE = 'tag.delete';

    case IMAGE_UPLOAD = 'image.upload';
    case IMAGE_DELETE = 'image.delete';

    case FORM_SUBMIT = 'form.submit';

    case SYSTEM_ERROR = 'system.error';
    case SECURITY_ALERT = 'security.alert';

    public function getCategory(): string
    {
        return match ($this) {
            self::USER_LOGIN, self::USER_LOGOUT, self::USER_REGISTER,
            self::USER_UPDATE, self::USER_DELETE,
            self::LOGIN_SUCCESS, self::LOGIN_FAILED, self::REGISTRATION, self::LOGOUT, self::AUTHORIZATION_DENIED => 'user',

            self::ARTICLE_CREATE, self::ARTICLE_UPDATE, self::ARTICLE_DELETE,
            self::ARTICLE_PUBLISH => 'article',

            self::CATEGORY_CREATE, self::CATEGORY_UPDATE, self::CATEGORY_DELETE => 'category',

            self::TAG_CREATE, self::TAG_UPDATE, self::TAG_DELETE => 'tag',

            self::IMAGE_UPLOAD, self::IMAGE_DELETE => 'image',

            self::FORM_SUBMIT => 'form',

            self::SYSTEM_ERROR, self::SECURITY_ALERT => 'system',
        };
    }

    public function getSeverity(): string
    {
        return match ($this) {
            self::USER_DELETE, self::ARTICLE_DELETE,
            self::SYSTEM_ERROR, self::SECURITY_ALERT, self::LOGIN_FAILED => 'high',

            self::USER_UPDATE, self::ARTICLE_UPDATE,
            self::CATEGORY_DELETE, self::TAG_DELETE,
            self::IMAGE_DELETE, self::AUTHORIZATION_DENIED => 'medium',

            default => 'low',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::USER_LOGIN, self::LOGIN_SUCCESS => 'User logged in',
            self::LOGIN_FAILED => 'Login failed',
            self::USER_LOGOUT, self::LOGOUT => 'User logged out',
            self::USER_REGISTER, self::REGISTRATION => 'New user registered',
            self::USER_UPDATE => 'User profile updated',
            self::USER_DELETE => 'User account deleted',
            self::AUTHORIZATION_DENIED => 'Authorization denied',

            self::ARTICLE_CREATE => 'Article created',
            self::ARTICLE_UPDATE => 'Article updated',
            self::ARTICLE_DELETE => 'Article deleted',
            self::ARTICLE_PUBLISH => 'Article published',

            self::CATEGORY_CREATE => 'Category created',
            self::CATEGORY_UPDATE => 'Category updated',
            self::CATEGORY_DELETE => 'Category deleted',

            self::TAG_CREATE => 'Tag created',
            self::TAG_UPDATE => 'Tag updated',
            self::TAG_DELETE => 'Tag deleted',

            self::IMAGE_UPLOAD => 'Image uploaded',
            self::IMAGE_DELETE => 'Image deleted',

            self::FORM_SUBMIT => 'Form submitted',

            self::SYSTEM_ERROR => 'System error occurred',
            self::SECURITY_ALERT => 'Security alert triggered',
        };
    }
}
