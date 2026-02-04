<?php

declare(strict_types=1);

namespace Blog\Domain\Audit\ValueObject;

class AuditEventType
{
    public const LOGIN_SUCCESS = 'login_success';
    public const LOGIN_FAILED = 'login_failed';
    public const LOGOUT = 'logout';
    public const REGISTRATION = 'registration';
    public const PASSWORD_RESET_REQUEST = 'password_reset_request';
    public const PASSWORD_RESET_COMPLETE = 'password_reset_complete';
    public const AUTHORIZATION_DENIED = 'authorization_denied';
    public const SESSION_EXPIRED = 'session_expired';
    public const SESSION_HIJACK = 'session_hijack';

    private string $value;

    public function __construct(string $value)
    {
        if (!in_array($value, [
            self::LOGIN_SUCCESS, self::LOGIN_FAILED, self::LOGOUT,
            self::REGISTRATION, self::PASSWORD_RESET_REQUEST,
            self::PASSWORD_RESET_COMPLETE, self::AUTHORIZATION_DENIED,
            self::SESSION_EXPIRED, self::SESSION_HIJACK,
        ])) {
            throw new \InvalidArgumentException('Invalid audit event type');
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
