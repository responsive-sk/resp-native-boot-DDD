<?php

declare(strict_types=1);

use Blog\Database\DatabaseManager;
use Blog\Domain\Audit\Repository\AuditLogRepository;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\User\Repository\UserRepositoryInterface;
use Blog\Infrastructure\Persistence\Doctrine\DoctrineArticleRepository;
use Blog\Infrastructure\Persistence\Doctrine\DoctrineAuditLogRepository;
use Blog\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use Psr\Container\ContainerInterface;

return [
    ArticleRepository::class => fn() => new DoctrineArticleRepository(
        DatabaseManager::getConnection('articles')
    ),

    \Blog\Domain\Blog\Repository\CategoryRepository::class => fn() => new \Blog\Infrastructure\Persistence\Doctrine\DoctrineCategoryRepository(
        DatabaseManager::getConnection('articles')
    ),

    \Blog\Domain\Blog\Repository\TagRepository::class => fn() => new \Blog\Infrastructure\Persistence\Doctrine\DoctrineTagRepository(
        DatabaseManager::getConnection('articles')
    ),

    AuditLogRepository::class => fn(ContainerInterface $c) => new DoctrineAuditLogRepository(
        DatabaseManager::getConnection("app")
    ),

    UserRepositoryInterface::class => fn(ContainerInterface $c) => new DoctrineUserRepository(
        DatabaseManager::getConnection('users'),
        require __DIR__ . '/../app/password_strength.php'
    ),

    \Blog\Domain\Form\Repository\FormRepositoryInterface::class => fn() => new \Blog\Infrastructure\Persistence\Doctrine\DoctrineFormRepository(
        DatabaseManager::getConnection('forms')
    ),
];
