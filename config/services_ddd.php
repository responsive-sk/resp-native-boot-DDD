<?php
// config/services_ddd.php - COMPLETE WORKING VERSION WITH API ROUTES
declare(strict_types=1);

use Blog\Database\Database;
use Blog\Database\DatabaseManager;
use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\User\Repository\UserRepositoryInterface;
use Blog\Infrastructure\Persistence\Doctrine\DoctrineArticleRepository;
use Blog\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;

// === APPLICATION USE-CASES ===
use Blog\Application\Blog\CreateArticle;
use Blog\Application\Blog\GetAllArticles;
use Blog\Application\Blog\UpdateArticle;
use Blog\Application\Blog\DeleteArticle;
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

    Database::class => fn() => DatabaseManager::getConnection('app'),

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
    Router::class => fn(ContainerInterface $c) => (function() use ($c) {
        $router = new Router();
        
        // === PUBLIC WEB ROUTES ===
        $router->get('/', 'home', fn($req) => $c->get(BlogController::class)->home($req));
        $router->get('/about', 'about', fn($req) => $c->get(BlogController::class)->about($req));
        $router->get('/blog', 'blog_index', fn($req) => $c->get(BlogController::class)->index($req));
        $router->get('/blog/{id:[0-9]+}', 'blog_show', fn($req) => $c->get(BlogController::class)->show($req));
        $router->get('/blog/{slug:[a-z0-9\-]+}', 'blog_show_slug', fn($req) => $c->get(BlogController::class)->showBySlug($req));
        
        // === AUTH WEB ROUTES ===
        $router->get('/login', 'login_form', fn($req) => $c->get(AuthController::class)->loginForm($req));
        $router->post('/login', 'login', fn($req) => $c->get(AuthController::class)->login($req));
        $router->get('/register', 'register_form', fn($req) => $c->get(AuthController::class)->registerForm($req));
        $router->post('/register', 'register', fn($req) => $c->get(AuthController::class)->register($req));
        $router->get('/logout', 'logout', fn($req) => $c->get(AuthController::class)->logout($req));
        
        // === ARTICLE MANAGEMENT (WEB) ===
        $router->get('/article/create', 'article_create_form', fn($req) => $c->get(ArticleController::class)->createForm($req));
        $router->post('/article/create', 'article_create', fn($req) => $c->get(ArticleController::class)->create($req));
        $router->get('/article/{id:[0-9]+}/edit', 'article_edit_form', fn($req) => $c->get(ArticleController::class)->editForm($req));
        $router->post('/article/{id:[0-9]+}/edit', 'article_update', fn($req) => $c->get(ArticleController::class)->update($req));
        $router->get('/article/{id:[0-9]+}/delete', 'article_delete', fn($req) => $c->get(ArticleController::class)->delete($req));

        // === MARK DASHBOARD ROUTES ===
        $router->get('/mark', 'mark_dashboard', fn($req) => $c->get(DashboardController::class)->index($req));
        $router->get('/mark/dashboard', 'mark_dashboard_alias', fn($req) => $c->get(DashboardController::class)->index($req));

        // === MARK ARTICLES MANAGEMENT ===
        $router->get('/mark/articles', 'mark_articles_list', fn($req) => $c->get(ArticlesController::class)->index($req));
        $router->get('/mark/articles/create', 'mark_article_create_form', fn($req) => $c->get(ArticlesController::class)->createForm($req));
        $router->post('/mark/articles/create', 'mark_article_create', fn($req) => $c->get(ArticlesController::class)->create($req));

        // Voliteľne – edit a delete (pridaj, ak už máš metódy v ArticlesController)
        $router->get('/mark/articles/{id:[0-9]+}/edit', 'mark_article_edit_form', fn($req) => $c->get(ArticlesController::class)->editForm($req));
        $router->post('/mark/articles/{id:[0-9]+}/edit', 'mark_article_update', fn($req) => $c->get(ArticlesController::class)->update($req));
        $router->get('/mark/articles/{id:[0-9]+}/delete', 'mark_article_delete', fn($req) => $c->get(ArticlesController::class)->delete($req));
        
        // === API ROUTES ===
        $router->get('/api/articles', 'api_articles_index', fn($req) => $c->get(ArticleApiController::class)->index($req));
        $router->get('/api/articles/{id:[0-9]+}', 'api_articles_show', fn($req) => $c->get(ArticleApiController::class)->show($req));
        $router->post('/api/articles', 'api_articles_create', fn($req) => $c->get(ArticleApiController::class)->create($req));
        $router->patch('/api/articles/{id:[0-9]+}', 'api_articles_update', fn($req) => $c->get(ArticleApiController::class)->update($req));
        $router->delete('/api/articles/{id:[0-9]+}', 'api_articles_delete', fn($req) => $c->get(ArticleApiController::class)->delete($req));
        $router->post('/api/auth/login', 'api_auth_login', fn($req) => $c->get(AuthApiController::class)->login($req));
        $router->post('/api/auth/register', 'api_auth_register', fn($req) => $c->get(AuthApiController::class)->register($req));
        
        return $router;
    })(),
    
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
