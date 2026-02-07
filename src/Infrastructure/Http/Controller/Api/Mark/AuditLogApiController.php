<?php
// resp-blog/src/Infrastructure/Http/Controller/Api/Mark/AuditLogApiController.php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Api\Mark;

use Blog\Infrastructure\Http\Controller\BaseController;
use Blog\Domain\Audit\Repository\AuditLogRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuditLogApiController extends BaseController
{
    private AuditLogRepository $auditLogRepository;
    
    public function __construct(AuditLogRepository $auditLogRepository)
    {
        $this->auditLogRepository = $auditLogRepository;
    }
    
    public function getRecent(ServerRequestInterface $request): ResponseInterface
    {
        $limit = (int)($request->getQueryParams()['limit'] ?? 10);
        $logs = $this->auditLogRepository->getRecentLogs($limit);
        
        $formattedLogs = array_map(function($log) {
            return [
                'id' => $log->getId()->value(),
                'eventType' => $log->getEventType()->value(),
                'description' => $log->getEventDescription(),
                'userId' => $log->getUserId(),
                'user' => $log->getUsername() ?? 'Unknown',
                'ipAddress' => $log->getIpAddress(),
                'userAgent' => $log->getUserAgent(),
                'createdAt' => $log->getCreatedAt()->format('c'),
                'time' => $this->formatTimeAgo($log->getCreatedAt()),
            ];
        }, $logs);
        
        return $this->json($formattedLogs);
    }
    
    private function formatTimeAgo(\DateTimeImmutable $date): string
    {
        $now = new \DateTimeImmutable();
        $diff = $now->diff($date);
        
        if ($diff->days === 0) {
            if ($diff->h === 0) {
                if ($diff->i === 0) {
                    return 'Just now';
                }
                return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
            }
            return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
        }
        
        return $diff->days . ' day' . ($diff->days > 1 ? 's' : '') . ' ago';
    }
}