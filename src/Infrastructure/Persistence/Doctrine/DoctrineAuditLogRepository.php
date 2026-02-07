<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Persistence\Doctrine;

use Blog\Domain\Audit\Entity\AuditLog;
use Blog\Domain\Audit\Repository\AuditLogRepository;
use Blog\Domain\Audit\ValueObject\AuditLogId;
use Blog\Domain\Audit\ValueObject\AuditEventType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;

class DoctrineAuditLogRepository implements AuditLogRepository
{
    public function __construct(private Connection $connection) 
    {
    }
    
    public function save(AuditLog $log): void
    {
        $data = [
            'id' => $log->getId()->value(),
            'user_id' => $log->getUserId(),
            'event_type' => $log->getEventType()->value(),
            'ip_address' => $log->getIpAddress(),
            'user_agent' => $log->getUserAgent(),
            'metadata' => json_encode($log->getMetadata()),
            'created_at' => $log->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
        
        $this->connection->insert('audit_logs', $data);
    }
    
    public function find(AuditLogId $id): ?AuditLog
    {
        $data = $this->connection->fetchAssociative(
            'SELECT id, user_id, event_type, ip_address, user_agent, metadata, created_at 
             FROM audit_logs 
             WHERE id = ?',
            [$id->value()]
        );
        
        if (!$data) {
            return null;
        }
        
        return $this->hydrate($data);
    }
    
    public function findByUserId(string $userId, ?int $limit = null): array
    {
        $sql = 'SELECT id, user_id, event_type, ip_address, user_agent, metadata, created_at 
                FROM audit_logs 
                WHERE user_id = ? 
                ORDER BY created_at DESC';
        
        if ($limit) {
            $sql .= ' LIMIT ' . (int)$limit;
        }
        
        $rows = $this->connection->fetchAllAssociative($sql, [$userId]);
        
        return array_map([$this, 'hydrate'], $rows);
    }
    
    public function findByEventType(string $eventType, ?int $limit = null): array
    {
        $sql = 'SELECT id, user_id, event_type, ip_address, user_agent, metadata, created_at 
                FROM audit_logs 
                WHERE event_type = ? 
                ORDER BY created_at DESC';
        
        if ($limit) {
            $sql .= ' LIMIT ' . (int)$limit;
        }
        
        $rows = $this->connection->fetchAllAssociative($sql, [$eventType]);
        
        return array_map([$this, 'hydrate'], $rows);
    }
    
    public function getRecentLogs(int $limit = 100): array
    {
        $sql = 'SELECT id, user_id, event_type, ip_address, user_agent, metadata, created_at 
                FROM audit_logs 
                ORDER BY created_at DESC 
                LIMIT ?';
        
        $rows = $this->connection->fetchAllAssociative(
            $sql, 
            [$limit], 
            [ParameterType::INTEGER]
        );
        
        return array_map([$this, 'hydrate'], $rows);
    }
    
    public function getStatistics(array $filters = []): array
    {
        $where = [];
        $params = [];
        $types = [];
        
        if (!empty($filters['start_date'])) {
            $where[] = 'created_at >= ?';
            $params[] = $filters['start_date'];
            $types[] = ParameterType::STRING;
        }
        
        if (!empty($filters['end_date'])) {
            $where[] = 'created_at <= ?';
            $params[] = $filters['end_date'];
            $types[] = ParameterType::STRING;
        }
        
        if (!empty($filters['event_type'])) {
            $where[] = 'event_type = ?';
            $params[] = $filters['event_type'];
            $types[] = ParameterType::STRING;
        }
        
        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        
        // Celkový počet
        $totalSql = "SELECT COUNT(*) as total FROM audit_logs {$whereClause}";
        $total = $this->connection->fetchOne($totalSql, $params, $types);
        
        // Počet podľa event type
        $byEventSql = "SELECT event_type, COUNT(*) as count 
                      FROM audit_logs 
                      {$whereClause} 
                      GROUP BY event_type 
                      ORDER BY count DESC";
        $byEvent = $this->connection->fetchAllAssociative($byEventSql, $params, $types);
        
        // Počet podľa dňa
        $byDaySql = "SELECT DATE(created_at) as date, COUNT(*) as count 
                    FROM audit_logs 
                    {$whereClause} 
                    GROUP BY DATE(created_at) 
                    ORDER BY date DESC 
                    LIMIT 30";
        $byDay = $this->connection->fetchAllAssociative($byDaySql, $params, $types);
        
        return [
            'total' => (int)$total,
            'by_event' => $byEvent,
            'by_day' => $byDay,
        ];
    }
    
    public function count(array $filters = []): int
    {
        $where = [];
        $params = [];
        $types = [];
        
        if (!empty($filters['start_date'])) {
            $where[] = 'created_at >= ?';
            $params[] = $filters['start_date'];
            $types[] = ParameterType::STRING;
        }
        
        if (!empty($filters['end_date'])) {
            $where[] = 'created_at <= ?';
            $params[] = $filters['end_date'];
            $types[] = ParameterType::STRING;
        }
        
        if (!empty($filters['event_type'])) {
            $where[] = 'event_type = ?';
            $params[] = $filters['event_type'];
            $types[] = ParameterType::STRING;
        }
        
        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        
        $sql = "SELECT COUNT(*) FROM audit_logs {$whereClause}";
        
        return (int)$this->connection->fetchOne($sql, $params, $types);
    }
    
    private function hydrate(array $data): AuditLog
    {
        $metadata = $data['metadata'] ? json_decode($data['metadata'], true) : [];
        
        return AuditLog::reconstitute(
            AuditLogId::fromString($data['id']),
            $data['user_id'],
            new AuditEventType($data['event_type']),
            $data['ip_address'],
            $data['user_agent'],
            $metadata,
            new \DateTimeImmutable($data['created_at'])
        );
    }
}
