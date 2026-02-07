<?php

declare(strict_types=1);

use Blog\Application\Audit\AuditLogger;
use Blog\Core\UseCaseHandler;
use Blog\Infrastructure\View\ViewRenderer;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Blog\Repository\CategoryRepository;
use Blog\Domain\Blog\Repository\TagRepository;
use Blog\Domain\User\Repository\UserRepositoryInterface;
use Blog\Security\AuthorizationService;
use Psr\Container\ContainerInterface;
use ResponsiveSk\Slim4Paths\Paths;
use ResponsiveSk\Slim4Session\SessionInterface;

return [
    // Web Controllers
    \Blog\Infrastructure\Http\Controller\Web\BlogController::class => fn (ContainerInterface $c) => new \Blog\Infrastructure\Http\Controller\Web\BlogController(
        $c,
        $c->get(UseCaseHandler::class),
        $c->get(ArticleRepository::class),
        $c->get(CategoryRepository::class),
        $c->get(ViewRenderer::class),
        $c->get(AuthorizationService::class)
    ),

    \Blog\Infrastructure\Http\Controller\Web\ArticleController::class => fn (ContainerInterface $c) => new \Blog\Infrastructure\Http\Controller\Web\ArticleController(
        $c,
        $c->get(UseCaseHandler::class),
        $c->get(ViewRenderer::class),
        $c->get(AuthorizationService::class)
    ),

    \Blog\Infrastructure\Http\Controller\Web\AuthController::class => fn (ContainerInterface $c) => new \Blog\Infrastructure\Http\Controller\Web\AuthController(
        $c,
        $c->get(UseCaseHandler::class),
        $c->get(ViewRenderer::class),
        $c->get(Paths::class),
        $c->get(AuditLogger::class),
        $c->get(SessionInterface::class),
        $c->get(CategoryRepository::class),
        $c->get(AuthorizationService::class)
    ),

    \Blog\Infrastructure\Http\Controller\Web\SearchController::class => fn (ContainerInterface $c) => new \Blog\Infrastructure\Http\Controller\Web\SearchController(
        $c,
        $c->get(UseCaseHandler::class),
        $c->get(ViewRenderer::class),
        $c->get(AuthorizationService::class)
    ),

    // Mark (System Operator) Controllers
    \Blog\Infrastructure\Http\Controller\Mark\DashboardController::class => fn (ContainerInterface $c) => new \Blog\Infrastructure\Http\Controller\Mark\DashboardController(
        $c,
        $c->get(UseCaseHandler::class),
        $c->get(ArticleRepository::class),
        $c->get(ViewRenderer::class),
        $c->get(AuthorizationService::class)
    ),

    \Blog\Infrastructure\Http\Controller\Mark\ArticlesController::class => fn (ContainerInterface $c) => new \Blog\Infrastructure\Http\Controller\Mark\ArticlesController(
        $c,
        $c->get(UseCaseHandler::class),
        $c->get(ArticleRepository::class),
        $c->get(ViewRenderer::class),
        $c->get(AuthorizationService::class)
    ),

    \Blog\Infrastructure\Http\Controller\Mark\CategoryController::class => fn (ContainerInterface $c) => new \Blog\Infrastructure\Http\Controller\Mark\CategoryController(
        $c,
        $c->get(UseCaseHandler::class),
        $c->get(CategoryRepository::class),
        $c->get(ViewRenderer::class),
        $c->get(AuthorizationService::class)
    ),

    \Blog\Infrastructure\Http\Controller\Mark\TagController::class => fn (ContainerInterface $c) => new \Blog\Infrastructure\Http\Controller\Mark\TagController(
        $c,
        $c->get(UseCaseHandler::class),
        $c->get(TagRepository::class),
        $c->get(\Blog\Application\Blog\GetAllTags::class),
        $c->get(\Blog\Application\Blog\GetOrCreateTag::class),
        $c->get(ViewRenderer::class),
        $c->get(AuthorizationService::class)
    ),

    \Blog\Infrastructure\Http\Controller\Mark\UsersController::class => fn (ContainerInterface $c) => new \Blog\Infrastructure\Http\Controller\Mark\UsersController(
        $c,
        $c->get(UseCaseHandler::class),
        $c->get(UserRepositoryInterface::class),
        $c->get(ViewRenderer::class),
        $c->get(AuthorizationService::class),
        $c->get(SessionInterface::class)
    ),

    // API Controllers
    \Blog\Infrastructure\Http\Controller\Api\ArticleApiController::class => fn (ContainerInterface $c) => new \Blog\Infrastructure\Http\Controller\Api\ArticleApiController(
        $c,
        $c->get(UseCaseHandler::class),
        $c->get(AuthorizationService::class)
    ),

    \Blog\Infrastructure\Http\Controller\Api\AuthApiController::class => fn (ContainerInterface $c) => new \Blog\Infrastructure\Http\Controller\Api\AuthApiController(
        $c,
        $c->get(UseCaseHandler::class),
        $c->get(AuthorizationService::class)
    ),

    \Blog\Infrastructure\Http\Controller\Api\SessionPingController::class => fn (ContainerInterface $c) => new \Blog\Infrastructure\Http\Controller\Api\SessionPingController(
        $c,
        $c->get(UseCaseHandler::class),
        $c->get(AuthorizationService::class)
    ),

    \Blog\Infrastructure\Http\Controller\DebugController::class => fn (ContainerInterface $c) => new \Blog\Infrastructure\Http\Controller\DebugController(),

    \Blog\Infrastructure\DebugBar\BlogDebugBarStyles::class => fn (ContainerInterface $c) => new \Blog\Infrastructure\DebugBar\BlogDebugBarStyles(),

    \Blog\Infrastructure\Http\Controller\Api\ImageController::class => fn (ContainerInterface $c) => new \Blog\Infrastructure\Http\Controller\Api\ImageController(
        $c,
        $c->get(UseCaseHandler::class),
        $c->get(AuthorizationService::class)
    ),
];
