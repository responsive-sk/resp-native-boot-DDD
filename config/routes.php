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
use Blog\Infrastructure\Http\Controller\Api\SessionPingController;
use Psr\Container\ContainerInterface;

return function (ContainerInterface $c): Router {
    $router = new Router();

    // === PUBLIC WEB ROUTES ===
    // === PUBLIC WEB ROUTES ===
    $router->get('/', 'home', fn($req) => $c->get(BlogController::class)->home($req));
    $router->get('/about', 'about', fn($req) => $c->get(BlogController::class)->about($req));
    $router->get('/contact', 'contact', fn($req) => $c->get(BlogController::class)->contact($req));
    $router->get('/blog', 'blog.index', fn($req) => $c->get(BlogController::class)->index($req));
    $router->get('/blog/{id:[0-9]+}', 'blog.show', fn($req) => $c->get(BlogController::class)->show($req));
    $router->get('/blog/{slug:[a-z0-9\-]+}', 'blog.show.slug', fn($req) => $c->get(BlogController::class)->showBySlug($req));
    $router->get('/search', 'search.index', fn($req) => $c->get(SearchController::class)->index($req));

    // === AUTH WEB ROUTES ===
    $router->get('/login', 'auth.login.form', fn($req) => $c->get(AuthController::class)->loginForm($req));
    $router->post('/login', 'auth.login', fn($req) => $c->get(AuthController::class)->login($req));
    $router->get('/register', 'auth.register.form', fn($req) => $c->get(AuthController::class)->registerForm($req));
    $router->post('/register', 'auth.register', fn($req) => $c->get(AuthController::class)->register($req));
    $router->get('/logout', 'auth.logout', fn($req) => $c->get(AuthController::class)->logout($req));

    // === ARTICLE MANAGEMENT (WEB) ===
    $router->get('/article/create', 'article.create.form', fn($req) => $c->get(ArticleController::class)->createForm($req));
    $router->post('/article/create', 'article.store', fn($req) => $c->get(ArticleController::class)->create($req));
    $router->get('/article/{id:[0-9]+}/edit', 'article.edit', fn($req) => $c->get(ArticleController::class)->editForm($req));
    $router->post('/article/{id:[0-9]+}/edit', 'article.update', fn($req) => $c->get(ArticleController::class)->update($req));
    $router->get('/article/{id:[0-9]+}/delete', 'article.delete', fn($req) => $c->get(ArticleController::class)->delete($req));

    // === MARK DASHBOARD ROUTES ===
    $router->get('/mark', 'mark.dashboard', fn($req) => $c->get(DashboardController::class)->index($req));
    $router->get('/mark/dashboard', 'mark.dashboard.alias', fn($req) => $c->get(DashboardController::class)->index($req));

    // === MARK ARTICLES MANAGEMENT ===
    $router->get('/mark/articles', 'mark.articles.index', fn($req) => $c->get(ArticlesController::class)->index($req));
    $router->get('/mark/articles/create', 'mark.articles.create', fn($req) => $c->get(ArticlesController::class)->createForm($req));
    $router->post('/mark/articles/create', 'mark.articles.store', fn($req) => $c->get(ArticlesController::class)->create($req));

    // Voliteľne – edit a delete (pridaj, ak už máš metódy v ArticlesController)
    $router->get('/mark/articles/{id:[0-9]+}/edit', 'mark.articles.edit', fn($req) => $c->get(ArticlesController::class)->editForm($req));
    $router->post('/mark/articles/{id:[0-9]+}/edit', 'mark.articles.update', fn($req) => $c->get(ArticlesController::class)->update($req));
    $router->get('/mark/articles/{id:[0-9]+}/delete', 'mark.articles.delete', fn($req) => $c->get(ArticlesController::class)->delete($req));

    // === MARK USERS MANAGEMENT ===
    $router->get('/mark/users', 'mark.users.index', fn($req) => $c->get(\Blog\Infrastructure\Http\Controller\Mark\UsersController::class)->index($req));
    $router->get('/mark/users/create', 'mark.users.create', fn($req) => $c->get(\Blog\Infrastructure\Http\Controller\Mark\UsersController::class)->createForm($req));
    $router->post('/mark/users/create', 'mark.users.store', fn($req) => $c->get(\Blog\Infrastructure\Http\Controller\Mark\UsersController::class)->create($req));
    $router->get('/mark/users/{id:[0-9a-fA-F\-]+}/edit', 'mark.users.edit', fn($req) => $c->get(\Blog\Infrastructure\Http\Controller\Mark\UsersController::class)->editForm($req));
    $router->post('/mark/users/{id:[0-9a-fA-F\-]+}/edit', 'mark.users.update', fn($req) => $c->get(\Blog\Infrastructure\Http\Controller\Mark\UsersController::class)->update($req));
    $router->get('/mark/users/{id:[0-9a-fA-F\-]+}/delete', 'mark.users.delete', fn($req) => $c->get(\Blog\Infrastructure\Http\Controller\Mark\UsersController::class)->delete($req));

    // === API ROUTES ===
    $router->get('/api/articles', 'api.articles.index', fn($req) => $c->get(ArticleApiController::class)->index($req));
    $router->get('/api/articles/{id:[0-9]+}', 'api.articles.show', fn($req) => $c->get(ArticleApiController::class)->show($req));
    $router->post('/api/articles', 'api.articles.store', fn($req) => $c->get(ArticleApiController::class)->create($req));
    $router->patch('/api/articles/{id:[0-9]+}', 'api.articles.update', fn($req) => $c->get(ArticleApiController::class)->update($req));
    $router->delete('/api/articles/{id:[0-9]+}', 'api.articles.delete', fn($req) => $c->get(ArticleApiController::class)->delete($req));
    $router->post('/api/auth/login', 'api.auth.login', fn($req) => $c->get(AuthApiController::class)->login($req));
    $router->post('/api/auth/register', 'api.auth.register', fn($req) => $c->get(AuthApiController::class)->register($req));
    $router->post('/api/session/ping', 'api.session.ping', fn($req) => $c->get(SessionPingController::class)->ping($req));

    // === FORM API ROUTES ===
    $router->post('/api/forms', 'api.forms.create', fn($req) => $c->get(\Blog\Infrastructure\Http\Controller\Form\FormController::class)->create($req));
    $router->get('/api/forms/{slug:[a-z0-9\-]+}', 'api.forms.get', fn($req, $slug) => $c->get(\Blog\Infrastructure\Http\Controller\Form\FormController::class)->get($req, $slug));


    return $router;
};
