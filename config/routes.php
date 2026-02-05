<?php

declare(strict_types=1);

use Blog\Core\Router;
use Blog\Infrastructure\Http\Controller\Api\ArticleApiController;
use Blog\Infrastructure\Http\Controller\Api\AuthApiController;
use Blog\Infrastructure\Http\Controller\Api\SessionPingController;
use Blog\Infrastructure\Http\Controller\Mark\ArticlesController;
use Blog\Infrastructure\Http\Controller\Mark\CategoryController;
use Blog\Infrastructure\Http\Controller\Mark\TagController;
use Blog\Infrastructure\Http\Controller\Mark\DashboardController;
use Blog\Infrastructure\Http\Controller\Web\ArticleController;
use Blog\Infrastructure\Http\Controller\Web\AuthController;
use Blog\Infrastructure\Http\Controller\Web\BlogController;
use Blog\Infrastructure\Http\Controller\Web\SearchController;
use Blog\Infrastructure\Http\Controller\DebugController;
use Psr\Container\ContainerInterface;

return function (ContainerInterface $c): Router {
    $router = new Router();

    // === DEBUG ERROR ROUTE ===
    $router->get('/debug/error', 'debug.error', fn($req) => $c->get(DebugController::class)->error($req));

    // === DEBUGBAR ASSETS ROUTE (MUSÍ BYŤ PRVÁ!) ===
    $debugbarConfig = require __DIR__ . '/debugbar.php';
    if ($debugbarConfig['debugbar']['enabled'] ?? false) {
        $router->get('/debugbar/{file:.+}', 'debugbar.assets', function ($req, $file) use ($c) {
            // Toto je placeholder, reálny handler je v middleware
            $factory = new \Nyholm\Psr7\Factory\Psr17Factory();
            return $factory->createResponse(200);
        });
    }



    // === PUBLIC BLOG ROUTES ===
    $router->get('/', 'home', fn($req) => $c->get(BlogController::class)->home($req));
    $router->get('/about', 'about', fn($req) => $c->get(BlogController::class)->about($req));
    $router->get('/contact', 'contact', fn($req) => $c->get(BlogController::class)->contact($req));
    $router->get('/blog', 'blog.index', fn($req) => $c->get(BlogController::class)->index($req));
    $router->get('/blog/{id:[0-9]+}', 'blog.show', fn($req) => $c->get(BlogController::class)->show($req));
    $router->get('/blog/{slug:[a-z0-9\-]+}', 'blog.show.slug', fn($req) => $c->get(BlogController::class)->showBySlug($req));
    $router->get('/category/{slug:[a-z0-9\-]+}', 'blog.category', fn($req, $slug) => $c->get(BlogController::class)->showCategory($req, $slug));
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

    // === MARK CATEGORIES MANAGEMENT ===
    $router->get('/mark/categories', 'mark.categories.index', fn($req) => $c->get(CategoryController::class)->index($req));
    $router->get('/mark/categories/create', 'mark.categories.create', fn($req) => $c->get(CategoryController::class)->createForm($req));
    $router->post('/mark/categories/create', 'mark.categories.store', fn($req) => $c->get(CategoryController::class)->create($req));
    $router->get('/mark/categories/{id:[0-9a-fA-F\-]+}/edit', 'mark.categories.edit', fn($req) => $c->get(CategoryController::class)->editForm($req, $req->getAttribute('id')));
    $router->post('/mark/categories/{id:[0-9a-fA-F\-]+}/edit', 'mark.categories.update', fn($req) => $c->get(CategoryController::class)->update($req, $req->getAttribute('id')));
    $router->get('/mark/categories/{id:[0-9a-fA-F\-]+}/delete', 'mark.categories.delete', fn($req) => $c->get(CategoryController::class)->delete($req, $req->getAttribute('id')));

    // === MARK TAGS MANAGEMENT ===
    $router->get('/mark/tags', 'mark.tags.index', fn($req) => $c->get(TagController::class)->index($req));
    $router->post('/mark/tags/create', 'mark.tags.create', fn($req) => $c->get(TagController::class)->create($req));
    $router->get('/mark/tags/{id:[0-9a-fA-F\-]+}/delete', 'mark.tags.delete', fn($req) => $c->get(TagController::class)->delete($req, $req->getAttribute('id')));

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

    // === IMAGE API ROUTES ===
    $router->post('/api/images/upload', 'api.images.upload', fn($req) => $c->get(\Blog\Infrastructure\Http\Controller\Api\ImageController::class)->upload($req));
    $router->delete('/api/images/{id:[0-9a-fA-F\-]+}', 'api.images.delete', fn($req, $id) => $c->get(\Blog\Infrastructure\Http\Controller\Api\ImageController::class)->delete($req, $id));
    $router->post('/api/images/attach', 'api.images.attach', fn($req) => $c->get(\Blog\Infrastructure\Http\Controller\Api\ImageController::class)->attachToArticle($req));
    $router->get('/api/images', 'api.images.list', fn($req) => $c->get(\Blog\Infrastructure\Http\Controller\Api\ImageController::class)->list($req));


    return $router;
};
