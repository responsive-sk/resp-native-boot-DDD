<?php

declare(strict_types=1);

namespace Blog\Application\Audit;

use Blog\Domain\Audit\Entity\AuditLog;
use Blog\Domain\Audit\Repository\AuditLogRepository;
use Blog\Domain\Audit\ValueObject\AuditLogId;
use Psr\Http\Message\ServerRequestInterface;

class AuditLogger
{
    public function __construct(
        private AuditLogRepository $repository
    ) {
    }

    public function logAuthentication(
        string $eventType,
        string $email,
        bool $success,
        ServerRequestInterface $request,
        array $context = []
    ): void {
        $log = AuditLog::createAuthenticationEvent(
            AuditLogId::generate(),
            $eventType,
            $email,
            $success,
            array_merge($context, [
                'ip_address' => $this->getClientIp($request),
                'user_agent' => $request->getHeaderLine('User-Agent'),
                'timestamp' => date('c'),
            ])
        );

        $this->repository->save($log);

        // Rate limiting alert pre failed logins
        if (!$success && $eventType === 'login_failed') {
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
        $log = AuditLog::createAuthorizationEvent(
            AuditLogId::generate(),
            $eventType,
            $userId,
            $resource,
            $granted,
            array_merge($context, [
                'ip_address' => $this->getClientIp($request),
                'user_agent' => $request->getHeaderLine('User-Agent'),
                'path' => $request->getUri()->getPath(),
                'method' => $request->getMethod(),
            ])
        );

        $this->repository->save($log);
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
}
