<?php

declare(strict_types=1);

namespace Blog\Application\Audit;

use Blog\Domain\Audit\Entity\AuditLog;
use Blog\Domain\Audit\Repository\AuditLogRepository;
use Blog\Domain\Audit\ValueObject\AuditEventType;
use Blog\Domain\Audit\AuditLogFactory;
use Psr\Http\Message\ServerRequestInterface;

class AuditLogger
{
    public function __construct(
        private AuditLogRepository $repository
    ) {
    }

    public function log(
        AuditEventType $eventType,
        ?string $userId,
        ?string $ipAddress,
        ?string $userAgent,
        array $metadata = []
    ): void {
        $auditLog = AuditLog::create(
            $eventType,
            $userId,
            $ipAddress,
            $userAgent,
            $metadata
        );
        
        $this->repository->save($auditLog);
    }

    public function logAuthentication(
        string $eventType,
        string $email,
        bool $success,
        ServerRequestInterface $request,
        array $context = []
    ): void {
        $ipAddress = $this->getClientIp($request);
        $userAgent = $request->getHeaderLine('User-Agent');
        
        $log = AuditLog::createAuthenticationEvent(
            $email,
            new AuditEventType($eventType),
            $success,
            $ipAddress,
            $userAgent,
            array_merge($context, [
                'timestamp' => date('c'),
            ])
        );

        $this->repository->save($log);

        // Rate limiting alert pre failed logins
        if (!$success && $eventType === AuditEventType::LOGIN_FAILED) {
            $this->checkFailedLoginRate($email, $request);
        }
    }

    public function logAuthorization(
        string $eventType,
        ?string $userId,
        string $resource,
        bool $granted,
        ServerRequestInterface $request,
        array $context = []
    ): void {
        $ipAddress = $this->getClientIp($request);
        $userAgent = $request->getHeaderLine('User-Agent');
        
        $log = AuditLog::createAuthorizationEvent(
            $userId,
            new AuditEventType($eventType),
            $resource,
            $granted,
            $ipAddress,
            $userAgent,
            array_merge($context, [
                'path' => $request->getUri()->getPath(),
                'method' => $request->getMethod(),
            ])
        );

        $this->repository->save($log);
    }

    // Helper methods using factory
    public function logLoginSuccess(
        string $email,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): void {
        $auditLog = AuditLogFactory::createLoginSuccess($email, $ipAddress, $userAgent);
        $this->repository->save($auditLog);
    }
    
    public function logLoginFailed(
        string $email,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): void {
        $auditLog = AuditLogFactory::createLoginFailed($email, $ipAddress, $userAgent);
        $this->repository->save($auditLog);
    }
    
    public function logAuthorizationDenied(
        ?string $userId,
        string $resource,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): void {
        $auditLog = AuditLogFactory::createAuthorizationDenied(
            $userId,
            $resource,
            $ipAddress,
            $userAgent
        );
        $this->repository->save($auditLog);
    }
    
    public function logArticleCreated(
        string $articleId,
        ?string $userId,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): void {
        $auditLog = AuditLogFactory::createArticleCreated(
            $articleId,
            $userId,
            $ipAddress,
            $userAgent
        );
        $this->repository->save($auditLog);
    }
    
    public function logImageUploaded(
        string $imageId,
        ?string $userId,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): void {
        $auditLog = AuditLogFactory::createImageUploaded(
            $imageId,
            $userId,
            $ipAddress,
            $userAgent
        );
        $this->repository->save($auditLog);
    }

    private function getClientIp(ServerRequestInterface $request): string
    {
        $serverParams = $request->getServerParams();

        // Check for proxy headers
        $headers = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        ];

        foreach ($headers as $header) {
            if (isset($serverParams[$header])) {
                foreach (explode(',', $serverParams[$header]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                        return $ip;
                    }
                }
            }
        }

        return $serverParams['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    private function checkFailedLoginRate(string $email, ServerRequestInterface $request): void
    {
        $ip = $this->getClientIp($request);
        $recentFailures = $this->repository->findFailedLogins($ip, new \DateTime('-15 minutes'));

        if (count($recentFailures) > 5) {
            // Alert - possible brute force attack
            error_log(sprintf(
                "[SECURITY ALERT] Multiple failed login attempts from IP %s for email %s",
                $ip,
                $email
            ));
        }
    }

    public function logLogin(string $userId): void
    {
        // Simple wrapper for now, assuming success since it's called after successful login
        // In a real scenario, we might want to pass the request object here too if needed
        // For now, we'll just log slightly less info or use a placeholder IP
        $log = AuditLog::createAuthenticationEvent(
            $userId,
            new AuditEventType(AuditEventType::LOGIN_SUCCESS),
            true,
            null,
            null,
            [
                'timestamp' => date('c'),
                'details' => 'User logged in successfully',
            ]
        );
        $this->repository->save($log);
    }

    public function logRegistration(string $userId): void
    {
        $log = AuditLog::createAuthenticationEvent(
            $userId,
            new AuditEventType(AuditEventType::REGISTRATION),
            true,
            null,
            null,
            [
                'timestamp' => date('c'),
                'details' => 'New user registered',
            ]
        );
        $this->repository->save($log);
    }

    public function logLogout(string $userId): void
    {
        $log = AuditLog::createAuthenticationEvent(
            $userId,
            new AuditEventType(AuditEventType::LOGOUT),
            true,
            null,
            null,
            [
                'timestamp' => date('c'),
                'details' => 'User logged out',
            ]
        );
        $this->repository->save($log);
    }
}