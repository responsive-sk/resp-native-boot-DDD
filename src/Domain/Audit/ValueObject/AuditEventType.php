<?php

// src/Domain/Audit/ValueObject/AuditEventType.php

declare(strict_types=1);

namespace Blog\Domain\Audit\ValueObject;

use InvalidArgumentException;

class AuditEventType
{
    // Security events
    public const LOGIN_SUCCESS = 'login_success';
    public const LOGIN_FAILED = 'login_failed';
    public const LOGOUT = 'logout';
    public const REGISTRATION = 'registration';
    public const PASSWORD_RESET_REQUEST = 'password_reset_request';
    public const PASSWORD_RESET_COMPLETE = 'password_reset_complete';
    public const AUTHORIZATION_DENIED = 'authorization_denied';
    public const SESSION_EXPIRED = 'session_expired';
    public const SESSION_HIJACK = 'session_hijack';
    public const AUTHENTICATION_REQUIRED = 'authentication_required'; // <-- TOTO CHÝBA!
    public const INVALID_CSRF = 'invalid_csrf';
    public const RATE_LIMIT_EXCEEDED = 'rate_limit_exceeded';

    // Article events
    public const ARTICLE_CREATED = 'article_created';
    public const ARTICLE_UPDATED = 'article_updated';
    public const ARTICLE_DELETED = 'article_deleted';
    public const ARTICLE_PUBLISHED = 'article_published';
    public const ARTICLE_VIEWED = 'article_viewed';
    public const ARTICLE_SEARCHED = 'article_searched';

    // User events
    public const USER_CREATED = 'user_created';
    public const USER_UPDATED = 'user_updated';
    public const USER_DELETED = 'user_deleted';
    public const USER_ROLE_CHANGED = 'user_role_changed';
    public const USER_PROFILE_UPDATED = 'user_profile_updated';

    // Image events
    public const IMAGE_UPLOADED = 'image_uploaded';
    public const IMAGE_DELETED = 'image_deleted';
    public const IMAGE_ATTACHED = 'image_attached';
    public const IMAGE_DETACHED = 'image_detached';

    // Form events
    public const FORM_CREATED = 'form_created';
    public const FORM_UPDATED = 'form_updated';
    public const FORM_SUBMITTED = 'form_submitted';
    public const FORM_DELETED = 'form_deleted';

    // Category/Tag events
    public const CATEGORY_CREATED = 'category_created';
    public const CATEGORY_UPDATED = 'category_updated';
    public const CATEGORY_DELETED = 'category_deleted';
    public const TAG_CREATED = 'tag_created';
    public const TAG_UPDATED = 'tag_updated';
    public const TAG_DELETED = 'tag_deleted';

    // System events
    public const SYSTEM_BACKUP = 'system_backup';
    public const SYSTEM_UPDATE = 'system_update';
    public const ERROR_OCCURRED = 'error_occurred';
    public const MAINTENANCE_MODE_ON = 'maintenance_mode_on';
    public const MAINTENANCE_MODE_OFF = 'maintenance_mode_off';
    
    // API events
    public const API_REQUEST = 'api_request';
    public const API_RESPONSE = 'api_response';
    public const API_ERROR = 'api_error';

    private string $value;

    public function __construct(string $value)
    {
        $validTypes = self::getAllTypes();

        if (!isset($validTypes[$value])) {
            // Debug info
            error_log("Invalid audit event type: '$value'. Valid types: " . implode(', ', array_keys($validTypes)));

            throw new InvalidArgumentException("Invalid audit event type: '$value'");
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public static function getAllTypes(): array
    {
        return [
            // Security
            self::LOGIN_SUCCESS => 'Login Successful',
            self::LOGIN_FAILED => 'Login Failed',
            self::LOGOUT => 'User Logout',
            self::REGISTRATION => 'User Registration',
            self::PASSWORD_RESET_REQUEST => 'Password Reset Requested',
            self::PASSWORD_RESET_COMPLETE => 'Password Reset Completed',
            self::AUTHORIZATION_DENIED => 'Authorization Denied',
            self::SESSION_EXPIRED => 'Session Expired',
            self::SESSION_HIJACK => 'Session Hijack Detected',
            self::AUTHENTICATION_REQUIRED => 'Authentication Required',
            self::INVALID_CSRF => 'Invalid CSRF Token',
            self::RATE_LIMIT_EXCEEDED => 'Rate Limit Exceeded',
            
            // Articles
            self::ARTICLE_CREATED => 'Article Created',
            self::ARTICLE_UPDATED => 'Article Updated',
            self::ARTICLE_DELETED => 'Article Deleted',
            self::ARTICLE_PUBLISHED => 'Article Published',
            self::ARTICLE_VIEWED => 'Article Viewed',
            self::ARTICLE_SEARCHED => 'Article Searched',
            
            // Users
            self::USER_CREATED => 'User Created',
            self::USER_UPDATED => 'User Updated',
            self::USER_DELETED => 'User Deleted',
            self::USER_ROLE_CHANGED => 'User Role Changed',
            self::USER_PROFILE_UPDATED => 'User Profile Updated',
            
            // Images
            self::IMAGE_UPLOADED => 'Image Uploaded',
            self::IMAGE_DELETED => 'Image Deleted',
            self::IMAGE_ATTACHED => 'Image Attached to Article',
            self::IMAGE_DETACHED => 'Image Detached from Article',
            
            // Forms
            self::FORM_CREATED => 'Form Created',
            self::FORM_UPDATED => 'Form Updated',
            self::FORM_SUBMITTED => 'Form Submitted',
            self::FORM_DELETED => 'Form Deleted',
            
            // Categories/Tags
            self::CATEGORY_CREATED => 'Category Created',
            self::CATEGORY_UPDATED => 'Category Updated',
            self::CATEGORY_DELETED => 'Category Deleted',
            self::TAG_CREATED => 'Tag Created',
            self::TAG_UPDATED => 'Tag Updated',
            self::TAG_DELETED => 'Tag Deleted',
            
            // System
            self::SYSTEM_BACKUP => 'System Backup',
            self::SYSTEM_UPDATE => 'System Update',
            self::ERROR_OCCURRED => 'Error Occurred',
            self::MAINTENANCE_MODE_ON => 'Maintenance Mode Enabled',
            self::MAINTENANCE_MODE_OFF => 'Maintenance Mode Disabled',
            
            // API
            self::API_REQUEST => 'API Request',
            self::API_RESPONSE => 'API Response',
            self::API_ERROR => 'API Error',
        ];
    }

    public static function isValid(string $value): bool
    {
        return isset(self::getAllTypes()[$value]);
    }

    public function equals(AuditEventType $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
