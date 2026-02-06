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
    
    // Article events (pridaj tieto!)
    public const ARTICLE_CREATED = 'article_created';
    public const ARTICLE_UPDATED = 'article_updated';
    public const ARTICLE_DELETED = 'article_deleted';
    public const ARTICLE_PUBLISHED = 'article_published';
    public const ARTICLE_VIEWED = 'article_viewed';
    
    // User events
    public const USER_CREATED = 'user_created';
    public const USER_UPDATED = 'user_updated';
    public const USER_DELETED = 'user_deleted';
    public const USER_ROLE_CHANGED = 'user_role_changed';
    
    // Image events
    public const IMAGE_UPLOADED = 'image_uploaded';
    public const IMAGE_DELETED = 'image_deleted';
    public const IMAGE_ATTACHED = 'image_attached';
    
    // Form events
    public const FORM_CREATED = 'form_created';
    public const FORM_UPDATED = 'form_updated';
    public const FORM_SUBMITTED = 'form_submitted';
    
    // System events
    public const SYSTEM_BACKUP = 'system_backup';
    public const SYSTEM_UPDATE = 'system_update';
    public const ERROR_OCCURRED = 'error_occurred';

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
            
            // Articles
            self::ARTICLE_CREATED => 'Article Created',
            self::ARTICLE_UPDATED => 'Article Updated',
            self::ARTICLE_DELETED => 'Article Deleted',
            self::ARTICLE_PUBLISHED => 'Article Published',
            self::ARTICLE_VIEWED => 'Article Viewed',
            
            // Users
            self::USER_CREATED => 'User Created',
            self::USER_UPDATED => 'User Updated',
            self::USER_DELETED => 'User Deleted',
            self::USER_ROLE_CHANGED => 'User Role Changed',
            
            // Images
            self::IMAGE_UPLOADED => 'Image Uploaded',
            self::IMAGE_DELETED => 'Image Deleted',
            self::IMAGE_ATTACHED => 'Image Attached to Article',
            
            // Forms
            self::FORM_CREATED => 'Form Created',
            self::FORM_UPDATED => 'Form Updated',
            self::FORM_SUBMITTED => 'Form Submitted',
            
            // System
            self::SYSTEM_BACKUP => 'System Backup',
            self::SYSTEM_UPDATE => 'System Update',
            self::ERROR_OCCURRED => 'Error Occurred',
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