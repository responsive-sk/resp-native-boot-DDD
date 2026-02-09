<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Middleware;

use Blog\Application\Audit\AuditLogger;
use Blog\Domain\Audit\ValueObject\AuditEventType;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuditLoggingMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AuditLogger $auditLogger
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        // Log authorization failures (403, 401)
        $statusCode = $response->getStatusCode();
        if ($statusCode === 401 || $statusCode === 403) {
            $userId = $_SESSION['user_id'] ?? null;
            $resource = $request->getUri()->getPath();

            $this->auditLogger->logAuthorization(
                AuditEventType::AUTHORIZATION_DENIED,
                $userId,
                $resource,
                false,
                $request,
                ['status_code' => $statusCode]
            );
        }

        return $response;
    }
}
