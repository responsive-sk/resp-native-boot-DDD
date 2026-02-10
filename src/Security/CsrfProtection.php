<?php

declare(strict_types=1);

namespace Blog\Security;

use Blog\Security\Exception\CsrfTokenException;
use ResponsiveSk\Slim4Session\SessionInterface;

class CsrfProtection
{
    private string $tokenName = 'csrf_token';
    private int $tokenLength = 32;

    public function __construct(
        private readonly SessionInterface $session
    ) {}

    public function generateToken(): string
    {
        return bin2hex(random_bytes($this->tokenLength));
    }

    public function validateToken(string $token): bool
    {
        if (!$this->session->has($this->tokenName)) {
            return false;
        }

        return hash_equals($this->session->get($this->tokenName), $token);
    }

    public function getToken(): string
    {
        if (!$this->session->has($this->tokenName)) {
            $this->session->set($this->tokenName, $this->generateToken());
        }

        return $this->session->get($this->tokenName);
    }

    public function regenerateToken(): void
    {
        $this->session->set($this->tokenName, $this->generateToken());
    }

    /**
     * @throws CsrfTokenException If token is invalid
     */
    public function requireValidToken(string $token): void
    {
        if (!$this->validateToken($token)) {
            throw CsrfTokenException::invalid();
        }
    }


}
