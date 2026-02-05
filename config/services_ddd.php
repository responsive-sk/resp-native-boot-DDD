<?php
// config/services_ddd.php - KOMPLETNÁ REFAKTOROVANÁ VERZIA
declare(strict_types=1);

// === IMPORTS ===
use Blog\Application\Audit\AuditLogger;
use Blog\Application\Blog\CreateArticle;
use Blog\Application\Blog\DeleteArticle;
use Blog\Application\Blog\GetAllArticles;
use Blog\Application\Blog\SearchArticles;
use Blog\Application\Blog\UpdateArticle;
use Blog\Application\User\LoginUser;
use Blog\Application\User\RegisterUser;
use Blog\Core\Application;
use Blog\Core\ExceptionMiddleware;
use Blog\Core\Router;
use Blog\Core\RouterMiddleware;
use Blog\Core\UseCaseHandler;
use Blog\Core\UseCaseMapper;
use Blog\Database\Database;
use Blog\Database\DatabaseManager;
use Blog\Domain\Audit\Repository\AuditLogRepository;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\User\Repository\UserRepositoryInterface;
use Blog\Infrastructure\Http\Controller\Api\ArticleApiController;
use Blog\Infrastructure\Http\Controller\Api\AuthApiController;
use Blog\Infrastructure\Http\Controller\Api\SessionPingController;
use Blog\Infrastructure\Http\Controller\Mark\ArticlesController;
use Blog\Infrastructure\Http\Controller\Mark\DashboardController;
use Blog\Infrastructure\Http\Controller\Web\ArticleController;
use Blog\Infrastructure\Http\Controller\Web\AuthController;
use Blog\Infrastructure\Http\Controller\Web\BlogController;
use Blog\Infrastructure\Http\Controller\Web\SearchController;
use Blog\Infrastructure\Http\Middleware\CsrfMiddleware;
use Blog\Infrastructure\Http\Middleware\ErrorHandlerMiddleware;
use Blog\Infrastructure\Http\Middleware\RateLimitMiddleware;
use Blog\Infrastructure\Http\Middleware\RequestContextMiddleware;
use Blog\Infrastructure\Http\Middleware\SessionTimeoutMiddleware;
use Blog\Infrastructure\Persistence\Doctrine\DoctrineArticleRepository;
use Blog\Infrastructure\Persistence\Doctrine\DoctrineAuditLogRepository;
use Blog\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use Blog\Infrastructure\View\PlatesRenderer;
use Blog\Infrastructure\View\ViewRenderer;
use Blog\Middleware\AuthMiddleware;
use Blog\Middleware\CorsMiddleware;
use Blog\Middleware\PjaxMiddleware;
use Blog\Security\Authorization;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use ResponsiveSk\Slim4Paths\Paths;
use ResponsiveSk\Slim4Session\SessionFactory;
use ResponsiveSk\Slim4Session\SessionInterface;
use ResponsiveSk\Slim4Session\Middleware\SessionMiddleware;

// === FÁZA 1: ZÁKLADNÉ SLUŽBY A FACTORIES ===
$services = [
    // HTTP Factories (PSR-17)
    Psr17Factory::class => fn () => new Psr17Factory(),

    // Database
    Database::class => fn () => DatabaseManager::getConnection(),

    // Paths
    Paths::class => fn () => new Paths(__DIR__ . '/../'),

    // === CORE USE-CASE TOOLS ===
    UseCaseMapper::class => fn () => new UseCaseMapper(),
    UseCaseHandler::class => fn (ContainerInterface $c) => new UseCaseHandler(
        $c->get(UseCaseMapper::class)
    ),
];

// === FÁZA 2: VIEW RENDERER ===
$services += [
    PlatesRenderer::class => fn () => new PlatesRenderer(
        \Blog\Infrastructure\Paths::resourcesPath() . '/views'
    ),

    ViewRenderer::class => fn (ContainerInterface $c) => new ViewRenderer(
        $c->get(PlatesRenderer::class),
        require __DIR__ . '/pages.php'
    ),
];

// === FÁZA 3: REPOSITORIES ===
$services += [
    ArticleRepository::class => fn () => new DoctrineArticleRepository(
        DatabaseManager::getConnection('articles')
    ),

    \Blog\Domain\Blog\Repository\CategoryRepository::class => fn () => new \Blog\Infrastructure\Persistence\Doctrine\DoctrineCategoryRepository(
        DatabaseManager::getConnection('articles')
    ),

    \Blog\Domain\Blog\Repository\TagRepository::class => fn () => new \Blog\Infrastructure\Persistence\Doctrine\DoctrineTagRepository(
        DatabaseManager::getConnection('articles')
    ),

    AuditLogRepository::class => fn (ContainerInterface $c) => new DoctrineAuditLogRepository(
        $c->get(Database::class)
    ),

    UserRepositoryInterface::class => fn () => new DoctrineUserRepository(
        DatabaseManager::getConnection('users')
    ),

    \Blog\Domain\Form\Repository\FormRepositoryInterface::class => fn () => new \Blog\Infrastructure\Persistence\Doctrine\DoctrineFormRepository(
        DatabaseManager::getConnection('forms')
    ),
];

// === FÁZA 4: SESSION (slim4-session) ===
$services += [
    SessionInterface::class => function () {
        $config = require __DIR__ . '/session.php';
        return SessionFactory::create($config);
    },

    SessionMiddleware::class => fn (ContainerInterface $c) => new SessionMiddleware(
        $c->get(SessionInterface::class)
    ),
];

// === FÁZA 4.5: CLOUDINARY SERVICES ===
$services += [
    'cloudinary' => fn (ContainerInterface $c) => new \Cloudinary\Cloudinary($c->get('config')['cloudinary'] ?? []),
    
    'image_storage' => fn (ContainerInterface $c) => new \Blog\Infrastructure\Storage\CloudinaryStorage(
        $c->get('cloudinary'),
        $c->get('config')['image'] ?? []
    ),
    
    'image_processor' => fn (ContainerInterface $c) => new \Blog\Infrastructure\Image\CloudinaryImageProcessor(
        $c->get('cloudinary'),
        $c->get('config')['image']['transformations'] ?? []
    ),
    
    'image_uploader' => fn (ContainerInterface $c) => new \Blog\Infrastructure\Image\CloudinaryImageUploader(
        $c->get('image_storage'),
        $c->get('config')['image'] ?? []
    ),
    
    'image_factory' => fn () => new \Blog\Domain\Image\Factory\ImageFactory(),
    
    'image_repository' => fn (ContainerInterface $c) => new \Blog\Infrastructure\Persistence\Doctrine\DoctrineImageRepository(
        $c->get(Database::class)
    ),
];

// === FÁZA 5: APPLICATION USE-CASES ===
$services += [
    AuditLogger::class => fn (ContainerInterface $c) => new AuditLogger(
        $c->get(AuditLogRepository::class)
    ),

    CreateArticle::class => fn (ContainerInterface $c) => new CreateArticle(
        $c->get(ArticleRepository::class)
    ),

    GetAllArticles::class => fn (ContainerInterface $c) => new GetAllArticles(
        $c->get(ArticleRepository::class)
    ),

    UpdateArticle::class => fn (ContainerInterface $c) => new UpdateArticle(
        $c->get(ArticleRepository::class)
    ),

    DeleteArticle::class => fn (ContainerInterface $c) => new DeleteArticle(
        $c->get(ArticleRepository::class)
    ),

    LoginUser::class => fn (ContainerInterface $c) => new LoginUser(
        $c->get(UserRepositoryInterface::class)
    ),

    RegisterUser::class => fn (ContainerInterface $c) => new RegisterUser(
        $c->get(UserRepositoryInterface::class)
    ),

    SearchArticles::class => fn (ContainerInterface $c) => new SearchArticles(
        $c->get(ArticleRepository::class)
    ),

    \Blog\Application\Blog\GetArticleBySlug::class => fn (ContainerInterface $c) => new \Blog\Application\Blog\GetArticleBySlug(
        $c->get(ArticleRepository::class)
    ),

    \Blog\Application\Blog\GetAllTags::class => fn (ContainerInterface $c) => new \Blog\Application\Blog\GetAllTags(
        $c->get(\Blog\Domain\Blog\Repository\TagRepository::class)
    ),

    \Blog\Application\Blog\GetOrCreateTag::class => fn (ContainerInterface $c) => new \Blog\Application\Blog\GetOrCreateTag(
        $c->get(\Blog\Domain\Blog\Repository\TagRepository::class)
    ),

    \Blog\Application\Blog\ManageArticleTags::class => fn (ContainerInterface $c) => new \Blog\Application\Blog\ManageArticleTags(
        $c->get(\Blog\Domain\Blog\Repository\TagRepository::class),
        $c->get(\Blog\Application\Blog\GetOrCreateTag::class)
    ),

    \Blog\Application\Form\CreateForm::class => fn (ContainerInterface $c) => new \Blog\Application\Form\CreateForm(
        $c->get(\Blog\Domain\Form\Repository\FormRepositoryInterface::class)
    ),

    \Blog\Application\Form\GetForm::class => fn (ContainerInterface $c) => new \Blog\Application\Form\GetForm(
        $c->get(\Blog\Domain\Form\Repository\FormRepositoryInterface::class)
    ),
];

// === FÁZA 6: CONTROLLERS ===
$services += [
    // Web Controllers
    BlogController::class => fn (ContainerInterface $c) => new BlogController(
        $c->get(ArticleRepository::class),
        $c->get(\Blog\Domain\Blog\Repository\CategoryRepository::class),
        $c->get(ViewRenderer::class),
        $c->get(UseCaseHandler::class) // Pridané
    ),

    ArticleController::class => fn (ContainerInterface $c) => new ArticleController(
        $c->get(ArticleRepository::class),
        $c->get(CreateArticle::class),
        $c->get(ViewRenderer::class),
        $c->get(UseCaseHandler::class) // Pridané
    ),

    AuthController::class => fn (ContainerInterface $c) => new AuthController(
        $c->get(LoginUser::class),
        $c->get(RegisterUser::class),
        $c->get(ViewRenderer::class),
        $c->get(Paths::class),
        $c->get(AuditLogger::class),
        $c->get(SessionInterface::class),
        $c->get(\Blog\Domain\Blog\Repository\CategoryRepository::class),
        $c->get(UseCaseHandler::class) // Pridané
    ),

    SearchController::class => fn (ContainerInterface $c) => new SearchController(
        $c->get(SearchArticles::class),
        $c->get(ViewRenderer::class),
        $c->get(UseCaseHandler::class) // Pridané
    ),

    // Mark (System Operator) Controllers
    DashboardController::class => fn (ContainerInterface $c) => new DashboardController(
        $c->get(ArticleRepository::class),
        $c->get(ViewRenderer::class),
        $c->get(UseCaseHandler::class) // Pridané
    ),

    ArticlesController::class => fn (ContainerInterface $c) => new ArticlesController(
        $c->get(ArticleRepository::class),
        $c->get(ViewRenderer::class),
        $c->get(UseCaseHandler::class) // Pridané
    ),

    \Blog\Infrastructure\Http\Controller\Mark\CategoryController::class => fn (ContainerInterface $c) => new \Blog\Infrastructure\Http\Controller\Mark\CategoryController(
        $c->get(\Blog\Domain\Blog\Repository\CategoryRepository::class),
        $c->get(ViewRenderer::class),
        $c->get(UseCaseHandler::class) // Pridané
    ),

    \Blog\Infrastructure\Http\Controller\Mark\TagController::class => fn (ContainerInterface $c) => new \Blog\Infrastructure\Http\Controller\Mark\TagController(
        $c->get(\Blog\Domain\Blog\Repository\TagRepository::class),
        $c->get(\Blog\Application\Blog\GetAllTags::class),
        $c->get(\Blog\Application\Blog\GetOrCreateTag::class),
        $c->get(ViewRenderer::class),
        $c->get(UseCaseHandler::class) // Pridané
    ),

    \Blog\Infrastructure\Http\Controller\Mark\UsersController::class => fn (ContainerInterface $c) => new \Blog\Infrastructure\Http\Controller\Mark\UsersController(
        $c->get(UserRepositoryInterface::class),
        $c->get(ViewRenderer::class),
        $c->get(UseCaseHandler::class) // Pridané
    ),

    // API Controllers
    ArticleApiController::class => fn (ContainerInterface $c) => new ArticleApiController(
        $c->get(ArticleRepository::class),
        $c->get(GetAllArticles::class),
        $c->get(CreateArticle::class),
        $c->get(UpdateArticle::class),
        $c->get(DeleteArticle::class),
        $c->get(UseCaseHandler::class) // Pridané
    ),

    AuthApiController::class => fn (ContainerInterface $c) => new AuthApiController(
        $c->get(LoginUser::class),
        $c->get(RegisterUser::class),
        $c->get(UseCaseHandler::class) // Pridané
    ),

    \Blog\Infrastructure\Http\Controller\Form\FormController::class => fn (ContainerInterface $c) => new \Blog\Infrastructure\Http\Controller\Form\FormController(
        $c->get(\Blog\Application\Form\CreateForm::class),
        $c->get(\Blog\Application\Form\GetForm::class),
        $c->get(UseCaseHandler::class) // Pridané
    ),

    SessionPingController::class => fn () => new SessionPingController(),
];

// === FÁZA 7: ROUTER ===
$services += [
    Router::class => fn (ContainerInterface $c) => (require __DIR__ . '/routes.php')($c),
    'router' => fn ($c) => $c->get(Router::class), // Alias
];

// === FÁZA 8: MIDDLEWARE ===
$services += [
    RouterMiddleware::class => fn ($c) => new RouterMiddleware($c->get('router')),

    ExceptionMiddleware::class => fn (ContainerInterface $c) => new ExceptionMiddleware(
        $c->get(Psr17Factory::class),
        $c->get(ViewRenderer::class)
    ),

    AuthMiddleware::class => fn () => new AuthMiddleware(),
    ErrorHandlerMiddleware::class => fn (ContainerInterface $c) => new ErrorHandlerMiddleware(
        $c->get(ViewRenderer::class)
    ),
    RequestContextMiddleware::class => fn () => new RequestContextMiddleware(),
    SessionTimeoutMiddleware::class => fn (ContainerInterface $c) => new SessionTimeoutMiddleware(
        null,
        $c->get(Paths::class)
    ),
    CsrfMiddleware::class => fn () => new CsrfMiddleware(),
    RateLimitMiddleware::class => fn () => new RateLimitMiddleware(),
    CorsMiddleware::class => fn () => new CorsMiddleware(),
    PjaxMiddleware::class => fn () => new \Blog\Middleware\PjaxMiddleware(),
];

// === FÁZA 9: MIDDLEWARE STACK ===
$services['middlewares'] = function (ContainerInterface $c) {
    $middlewares = [];
    
    // Základný middleware stack
    $middlewares = array_merge($middlewares, [
        $c->get(ErrorHandlerMiddleware::class),
        $c->get(ExceptionMiddleware::class),
        $c->get(CorsMiddleware::class),
        $c->get(PjaxMiddleware::class),
        $c->get(RequestContextMiddleware::class),
        $c->get(SessionMiddleware::class), // slim4-session
        $c->get(SessionTimeoutMiddleware::class),
        $c->get(RateLimitMiddleware::class),
        // $c->get(CsrfMiddleware::class), // Temporárne vypnuté
        $c->get(AuthMiddleware::class),
        $c->get(RouterMiddleware::class),
    ]);
    
    return $middlewares;
};

// === FÁZA 10: APPLICATION ===
$services[Application::class] = function (ContainerInterface $c) {
    $app = new Application($c->get('middlewares'));
    
    return $app;
};

// === Vráť finálny array služieb ===
return $services;