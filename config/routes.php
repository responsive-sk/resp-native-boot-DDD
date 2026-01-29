<?php
declare(strict_types=1);

use Blog\Core\Router;
use Blog\Infrastructure\Http\Controller\Web\BlogController;
use Blog\Infrastructure\Http\Controller\Web\SearchController;
use Blog\Infrastructure\Http\Controller\Web\AuthController;
use Blog\Infrastructure\Http\Controller\Web\ArticleController;
use Blog\Infrastructure\Http\Controller\Mark\DashboardController;
use Blog\Infrastructure\Http\Controller\Mark\ArticlesController;
use Blog\Infrastructure\Http\Controller\Api\ArticleApiController;
use Blog\Infrastructure\Http\Controller\Api\AuthApiController;
use Psr\Container\ContainerInterface;

return function (ContainerInterface $c): Router {
    $router = new Router();

    // === PUBLIC WEB ROUTES ===
    $router->get('/', 'home', fn($req) => $c->get(BlogController::class)->home($req));
    $router->get('/about', 'about', fn($req) => $c->get(BlogController::class)->about($req));
    $router->get('/contact', 'contact', fn($req) => $c->get(BlogController::class)->contact($req));
    $router->get('/blog', 'blog_index', fn($req) => $c->get(BlogController::class)->index($req));
    $router->get('/blog/{id:[0-9]+}', 'blog_show', fn($req) => $c->get(BlogController::class)->show($req));
    $router->get('/blog/{slug:[a-z0-9\-]+}', 'blog_show_slug', fn($req) => $c->get(BlogController::class)->showBySlug($req));
    $router->get('/search', 'search_index', fn($req) => $c->get(SearchController::class)->index($req));

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
};
