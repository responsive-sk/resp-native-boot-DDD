<?php
// resp-blog/src/Infrastructure/Http/Controller/Api/Mark/DashboardApiController.php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Api\Mark;

use Blog\Infrastructure\Http\Controller\BaseController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Blog\Domain\Audit\Repository\AuditLogRepository;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\User\Repository\UserRepositoryInterface;

class DashboardApiController extends BaseController
{
    private AuditLogRepository $auditLogRepository;
    private ArticleRepository $articleRepository;
    private UserRepositoryInterface $userRepository;
    
    public function __construct(
        AuditLogRepository $auditLogRepository,
        ArticleRepository $articleRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->auditLogRepository = $auditLogRepository;
        $this->articleRepository = $articleRepository;
        $this->userRepository = $userRepository;
    }
    
    public function getStats(ServerRequestInterface $request): ResponseInterface
    {
        // Basic stats
        $stats = [
            'totalArticles' => $this->articleRepository->count(),
            'publishedArticles' => $this->articleRepository->count(['status' => 'published']),
            'draftArticles' => $this->articleRepository->count(['status' => 'draft']),
            'totalUsers' => $this->userRepository->count(),
            'adminUsers' => $this->userRepository->count(['role' => 'admin']),
            'authorUsers' => $this->userRepository->count(['role' => 'author']),
            'todayActivity' => $this->getTodayAuditCount(),
            'weekActivity' => $this->getWeekAuditCount(),
            'articlesTrend' => $this->getArticlesTrend(),
            'usersTrend' => $this->getUsersTrend(),
            'activityTrend' => $this->getActivityTrend(),
            'storageUsed' => $this->getStorageUsed(),
            'storageTotal' => 10240, // 10GB in MB
            'storageTrend' => 12, // 12% increase
        ];
        
        // Chart data - last 7 days activity
        $chartData = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Audit Logs',
                    'data' => [],
                    'backgroundColor' => 'rgba(14, 165, 233, 0.2)',
                    'borderColor' => 'rgba(14, 165, 233, 1)',
                    'borderWidth' => 2,
                ]
            ]
        ];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $chartData['labels'][] = date('M j', strtotime($date));
            $chartData['datasets'][0]['data'][] = $this->getAuditCountForDate($date);
        }
        
        $stats['chartData'] = $chartData;
        
        // Event distribution
        $eventStats = $this->auditLogRepository->getStatistics();
        $stats['eventDistribution'] = [
            'labels' => array_column($eventStats['by_event'] ?? [], 'event_type'),
            'datasets' => [[
                'data' => array_column($eventStats['by_event'] ?? [], 'count'),
                'backgroundColor' => [
                    '#0ea5e9', // blue
                    '#10b981', // green
                    '#f59e0b', // yellow
                    '#ef4444', // red
                    '#8b5cf6', // purple
                ]
            ]]
        ];
        
        return $this->json($stats);
    }
    
    public function getRecentLogs(ServerRequestInterface $request): ResponseInterface
    {
        $logs = $this->auditLogRepository->getRecentLogs(10);
        
        $formattedLogs = array_map(function($log) {
            return [
                'id' => $log->getId()->value(),
                'eventType' => $log->getEventType()->value(),
                'description' => $log->getEventDescription(),
                'userId' => $log->getUserId(),
                'ipAddress' => $log->getIpAddress(),
                'createdAt' => $log->getCreatedAt()->format('c'),
            ];
        }, $logs);
        
        return $this->json($formattedLogs);
    }
    
    public function getRecentArticles(ServerRequestInterface $request): ResponseInterface
    {
        $articles = $this->articleRepository->getRecentArticles(10);
        
        $formattedArticles = array_map(function($article) {
            return [
                'id' => $article->getId()->value(),
                'title' => $article->getTitle(),
                'excerpt' => $article->getExcerpt(),
                'status' => $article->getStatus(),
                'createdAt' => $article->getCreatedAt()->format('c'),
                'publishedAt' => $article->getPublishedAt() ? 
                    $article->getPublishedAt()->format('c') : null,
            ];
        }, $articles);
        
        return $this->json($formattedArticles);
    }
    
    public function getRecentUsers(ServerRequestInterface $request): ResponseInterface
    {
        $users = $this->userRepository->getRecentUsers(10);
        
        $formattedUsers = array_map(function($user) {
            return [
                'id' => $user->getId()->value(),
                'email' => $user->getEmail(),
                'username' => $user->getUsername(),
                'role' => $user->getRole(),
                'createdAt' => $user->getCreatedAt()->format('c'),
                'lastLoginAt' => $user->getLastLoginAt() ? 
                    $user->getLastLoginAt()->format('c') : null,
            ];
        }, $users);
        
        return $this->json($formattedUsers);
    }
    
    private function getTodayAuditCount(): int
    {
        $today = date('Y-m-d');
        return $this->auditLogRepository->count([
            'start_date' => $today . ' 00:00:00',
            'end_date' => $today . ' 23:59:59',
        ]);
    }
    
    private function getWeekAuditCount(): int
    {
        $weekStart = date('Y-m-d', strtotime('-7 days'));
        $today = date('Y-m-d');
        
        return $this->auditLogRepository->count([
            'start_date' => $weekStart . ' 00:00:00',
            'end_date' => $today . ' 23:59:59',
        ]);
    }
    
    private function getAuditCountForDate(string $date): int
    {
        return $this->auditLogRepository->count([
            'start_date' => $date . ' 00:00:00',
            'end_date' => $date . ' 23:59:59',
        ]);
    }
    
    private function getArticlesTrend(): float
    {
        // Calculate percentage change from last week
        $currentWeek = $this->articleRepository->count([
            'start_date' => date('Y-m-d', strtotime('-7 days')) . ' 00:00:00',
            'end_date' => date('Y-m-d') . ' 23:59:59',
        ]);
        
        $previousWeek = $this->articleRepository->count([
            'start_date' => date('Y-m-d', strtotime('-14 days')) . ' 00:00:00',
            'end_date' => date('Y-m-d', strtotime('-7 days')) . ' 23:59:59',
        ]);
        
        if ($previousWeek === 0) return 100;
        return round((($currentWeek - $previousWeek) / $previousWeek) * 100, 1);
    }
    
    private function getUsersTrend(): float
    {
        // Similar calculation for users
        return 5.2; // Example static value
    }
    
    private function getActivityTrend(): float
    {
        // Similar calculation for activity
        return 8.7; // Example static value
    }
    
    private function getStorageUsed(): int
    {
        // Calculate storage used in MB
        // This would need actual file system scanning
        return 2456; // Example: 2.4GB
    }
}