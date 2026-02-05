<?php
// src/Infrastructure/Http/Controller/Web/AuthController.php - REFAKTOROVANÝ

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Web;

use Blog\Infrastructure\Http\Controller\BaseController;
use Blog\Infrastructure\View\ViewRenderer;
use Blog\Application\User\LoginUser;
use Blog\Application\User\RegisterUser;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Psr\Container\ContainerInterface;
use Blog\Core\UseCaseHandler;

class AuthController extends BaseController
{
    public function __construct(
        ContainerInterface $container,
        UseCaseHandler $useCaseHandler,
        private ViewRenderer $viewRenderer,
        private \ResponsiveSk\Slim4Paths\Paths $paths,
        private \Blog\Application\Audit\AuditLogger $auditLogger,
        private \ResponsiveSk\Slim4Session\SessionInterface $session,
        private \Blog\Domain\Blog\Repository\CategoryRepository $categoryRepository
    ) {
        parent::__construct($container, $useCaseHandler);
    }

    public function loginForm(ServerRequestInterface $request): ResponseInterface
    {
        $categories = $this->categoryRepository->getAll();

        return $this->viewRenderer->renderResponse('auth.login', [
            'categories' => $categories,
            'title' => 'Login',
        ]);
    }

    public function login(ServerRequestInterface $request): ResponseInterface
    {
        // POST request - spracovať login
        try {
            $useCase = $this->useCaseHandler->get(LoginUser::class);

            $result = $this->executeUseCase($request, $useCase, [
                'email' => 'body:email',
                'password' => 'body:password',
            ], 'array');

            if ($result['success']) {
                // Nastav session
                $this->session->set('user_id', $result['data']['user']['id']);
                $this->session->set('user_role', $result['data']['user']['role']);

                // Audit log
                $this->auditLogger->logLogin($result['data']['user']['id']);

                return $this->redirect($this->paths->urlFor('home'));
            }

            // Ak login zlyhal
            $categories = $this->categoryRepository->getAll();

            return $this->viewRenderer->renderResponse('auth.login', [
                'categories' => $categories,
                'title' => 'Login',
                'error' => 'Invalid credentials',
            ], 401);

        } catch (\DomainException $e) {
            $categories = $this->categoryRepository->getAll();

            return $this->viewRenderer->renderResponse('auth.login', [
                'categories' => $categories,
                'title' => 'Login',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function registerForm(ServerRequestInterface $request): ResponseInterface
    {
        $categories = $this->categoryRepository->getAll();

        return $this->viewRenderer->renderResponse('auth.register', [
            'categories' => $categories,
            'title' => 'Register',
        ]);
    }

    public function register(ServerRequestInterface $request): ResponseInterface
    {
        // POST request - spracovať registráciu
        try {
            $useCase = $this->useCaseHandler->get(RegisterUser::class);

            $result = $this->executeUseCase($request, $useCase, [
                'email' => 'body:email',
                'password' => 'body:password',
                'name' => 'body:name',
            ], 'web');

            // Automatický login po úspešnej registrácii
            if ($result['success']) {
                $this->session->set('user_id', $result['user']['id']);
                $this->session->set('user_role', $result['user']['role']);

                $this->auditLogger->logRegistration($result['user']['id']);

                return $this->redirect($this->paths->urlFor('home'));
            }

            $categories = $this->categoryRepository->getAll();

            return $this->viewRenderer->renderResponse('auth.register', [
                'categories' => $categories,
                'title' => 'Register',
                'error' => 'Registration failed',
            ], 400);

        } catch (\DomainException $e) {
            $categories = $this->categoryRepository->getAll();

            return $this->viewRenderer->renderResponse('auth.register', [
                'categories' => $categories,
                'title' => 'Register',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function logout(ServerRequestInterface $request): ResponseInterface
    {
        $userId = $this->session->get('user_id');

        if ($userId) {
            $this->auditLogger->logLogout($userId);
        }

        $this->session->destroy();

        return $this->redirect($this->paths->urlFor('home'));
    }
}