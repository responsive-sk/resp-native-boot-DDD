<?php
// config/services_ddd.php - COMPLETE WORKING VERSION WITH API ROUTES
declare(strict_types=1);


use Blog\Database\Database;
use Blog\Database\DatabaseManager;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\User\Repository\UserRepositoryInterface;
use Blog\Infrastructure\Http\Controller\Web\SearchController;
use Blog\Infrastructure\Persistence\Doctrine\DoctrineArticleRepository;
use Blog\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;

// === APPLICATION USE-CASES ===
use Blog\Application\Blog\CreateArticle;
use Blog\Application\Blog\GetAllArticles;
use Blog\Application\Blog\UpdateArticle;
use Blog\Application\Blog\DeleteArticle;
use Blog\Application\Blog\SearchArticles;
use Blog\Application\User\LoginUser;
use Blog\Application\User\RegisterUser;

// === CONTROLLERS ===
use Blog\Infrastructure\Http\Controller\Web\BlogController;
use Blog\Infrastructure\Http\Controller\Web\ArticleController;
use Blog\Infrastructure\Http\Controller\Web\AuthController;
use Blog\Infrastructure\Http\Controller\Mark\DashboardController;
use Blog\Infrastructure\Http\Controller\Mark\ArticlesController;
use Blog\Infrastructure\Http\Controller\Api\ArticleApiController;
use Blog\Infrastructure\Http\Controller\Api\AuthApiController;

// === MIDDLEWARE ===
use Blog\Middleware\SessionMiddleware;
use Blog\Middleware\AuthMiddleware;
use Blog\Middleware\CorsMiddleware;
use Blog\Infrastructure\Http\Middleware\ErrorHandlerMiddleware;
use Blog\Infrastructure\Http\Middleware\RequestContextMiddleware;

// === CORE ===
use Blog\Core\Router;
use Blog\Core\RouterMiddleware;
use Blog\Core\ExceptionMiddleware;
use Blog\Core\Application;
use Nyholm\Psr7\Factory\Psr17Factory;

use Psr\Container\ContainerInterface;
use Blog\Infrastructure\View\PlatesRenderer;
use Blog\Infrastructure\View\ViewRenderer;

return [
        // === FACTORIES ===
    Psr17Factory::class => fn() => new Psr17Factory(),

    Database::class => fn() => DatabaseManager::getConnection(),

        // === VIEW RENDERER ===
    PlatesRenderer::class => fn() => new PlatesRenderer(
        __DIR__ . '/../resources/views'
    ),

    ViewRenderer::class => fn(ContainerInterface $c) => new ViewRenderer(
        $c->get(PlatesRenderer::class),
        require __DIR__ . '/pages.php'
    ),

        // === REPOSITORIES ===
    ArticleRepository::class => fn() => new DoctrineArticleRepository(
        DatabaseManager::getConnection('articles')
    ),

    UserRepositoryInterface::class => fn() => new DoctrineUserRepository(
        DatabaseManager::getConnection('users')
    ),

    \Blog\Domain\Form\Repository\FormRepositoryInterface::class => fn() => new \Blog\Infrastructure\Persistence\Doctrine\DoctrineFormRepository(
        DatabaseManager::getConnection('forms')
    ),

        // === APPLICATION USE-CASES ===
    CreateArticle::class => fn(ContainerInterface $c) => new CreateArticle(
        $c->get(ArticleRepository::class)
    ),

    GetAllArticles::class => fn(ContainerInterface $c) => new GetAllArticles(
        $c->get(ArticleRepository::class)
    ),

    UpdateArticle::class => fn(ContainerInterface $c) => new UpdateArticle(
        $c->get(ArticleRepository::class)
    ),

    DeleteArticle::class => fn(ContainerInterface $c) => new DeleteArticle(
        $c->get(ArticleRepository::class)
    ),

    LoginUser::class => fn(ContainerInterface $c) => new LoginUser(
        $c->get(UserRepositoryInterface::class)
    ),

    RegisterUser::class => fn(ContainerInterface $c) => new RegisterUser(
        $c->get(UserRepositoryInterface::class)
    ),

    SearchArticles::class => fn(ContainerInterface $c) => new SearchArticles(
        $c->get(ArticleRepository::class)
    ),

    \Blog\Application\Form\CreateForm::class => fn(ContainerInterface $c) => new \Blog\Application\Form\CreateForm(
        $c->get(\Blog\Domain\Form\Repository\FormRepositoryInterface::class)
    ),

    \Blog\Application\Form\GetForm::class => fn(ContainerInterface $c) => new \Blog\Application\Form\GetForm(
        $c->get(\Blog\Domain\Form\Repository\FormRepositoryInterface::class)
    ),

        // === CONTROLLERS ===
        // Web
    BlogController::class => fn(ContainerInterface $c) => new BlogController(
        $c->get(ArticleRepository::class),
        $c->get(ViewRenderer::class)
    ),

    ArticleController::class => fn(ContainerInterface $c) => new ArticleController(
        $c->get(ArticleRepository::class),
        $c->get(CreateArticle::class),
        $c->get(ViewRenderer::class)
    ),

    AuthController::class => fn(ContainerInterface $c) => new AuthController(
        $c->get(LoginUser::class),
        $c->get(RegisterUser::class),
        $c->get(ViewRenderer::class)
    ),

    SearchController::class => fn(ContainerInterface $c) => new SearchController(
        $c->get(SearchArticles::class),
        $c->get(ViewRenderer::class)
    ),

        // Mark (System Operator)
    DashboardController::class => fn(ContainerInterface $c) => new DashboardController(
        $c->get(ArticleRepository::class),
        $c->get(ViewRenderer::class)
    ),

    ArticlesController::class => fn(ContainerInterface $c) => new ArticlesController(
        $c->get(ArticleRepository::class),
        $c->get(ViewRenderer::class)
    ),

        // API
    ArticleApiController::class => fn(ContainerInterface $c) => new ArticleApiController(
        $c->get(ArticleRepository::class),
        $c->get(GetAllArticles::class),
        $c->get(CreateArticle::class),
        $c->get(UpdateArticle::class),
        $c->get(DeleteArticle::class)
    ),

    AuthApiController::class => fn(ContainerInterface $c) => new AuthApiController(
        $c->get(LoginUser::class),
        $c->get(RegisterUser::class)
    ),

        // === ROUTER ===
    Router::class => fn(ContainerInterface $c) => (require __DIR__ . '/routes.php')($c),

    // Alias pre 'router' key
    'router' => fn($c) => $c->get(Router::class),

        // === MIDDLEWARE ===
    RouterMiddleware::class => fn($c) => new RouterMiddleware($c->get('router')),

    ExceptionMiddleware::class => fn(ContainerInterface $c) => new ExceptionMiddleware(
        $c->get(Psr17Factory::class),
        true
    ),

    AuthMiddleware::class => fn() => new AuthMiddleware(),
    ErrorHandlerMiddleware::class => fn() => new ErrorHandlerMiddleware(getenv('APP_ENV') ?: 'dev'),
    RequestContextMiddleware::class => fn() => new RequestContextMiddleware(),
    SessionMiddleware::class => fn() => new SessionMiddleware(),
    CorsMiddleware::class => fn() => new CorsMiddleware(),

    'middlewares' => fn(ContainerInterface $c) => [
        $c->get(ErrorHandlerMiddleware::class),
        $c->get(ExceptionMiddleware::class),
        $c->get(CorsMiddleware::class),
        $c->get(RequestContextMiddleware::class),
        $c->get(SessionMiddleware::class),
        $c->get(AuthMiddleware::class),
        $c->get(RouterMiddleware::class),
    ],


        // === APPLICATION ===
    Application::class => fn(ContainerInterface $c) => new Application(
        $c->get('middlewares')
    ),
];
