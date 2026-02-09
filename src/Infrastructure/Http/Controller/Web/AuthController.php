<?php

// src/Infrastructure/Http/Controller/Web/AuthController.php - REFAKTOROVANÝ

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Web;

use Blog\Application\User\LoginUser;
use Blog\Application\User\RegisterUser;
use Blog\Core\UseCaseHandler;
use Blog\Infrastructure\Http\Controller\BaseController;
use Blog\Infrastructure\View\ViewRenderer;
use Blog\Domain\Audit\ValueObject\AuditEventType;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends BaseController
{
    public function __construct(
        ContainerInterface $container,
        UseCaseHandler $useCaseHandler,
        private ViewRenderer $viewRenderer,
        private \ResponsiveSk\Slim4Paths\Paths $paths,
        private \Blog\Application\Audit\AuditLogger $auditLogger,
        private \ResponsiveSk\Slim4Session\SessionInterface $session,
        private \Blog\Domain\Blog\Repository\CategoryRepository $categoryRepository,
        \Blog\Security\AuthorizationService $authorization
    ) {
        parent::__construct($container, $useCaseHandler, $authorization);
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
                // Prevent session fixation
                $this->session->regenerateId();

                // Nastav session
                $this->session->set('user_id', $result['data']['user']['id']);
                $this->session->set('user_role', $result['data']['user']['role']);
                $this->session->set('last_activity', time());
                $this->session->set('fingerprint', $this->generateFingerprint($request)); # Add fingerprint

                // Audit log
                $this->auditLogger->logLogin($result['data']['user']['id']);

                // Determine redirect route based on user role
                $redirectRouteName = 'home'; // Default redirect
                if ($result['data']['user']['role'] === 'ROLE_MARK') {
                    $redirectRouteName = 'mark.dashboard';
                }

                // SECURITY: Use safe redirect to prevent open redirect attacks
                $safeRedirect = $this->getSafeRedirect($request, $this->paths->urlFor($redirectRouteName));

                return $this->redirect($safeRedirect);
            }

            // Ak login zlyhal
            $categories = $this->categoryRepository->getAll();

            // Audit log failure
            $this->auditLogger->logAuthentication(
                AuditEventType::LOGIN_FAILED,
                (string) ($request->getParsedBody()['email'] ?? 'unknown'),
                false,
                $request,
                ['reason' => 'invalid_credentials']
            );

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
                // Prevent session fixation
                $this->session->regenerateId();

                $this->session->set('user_id', $result['user']['id']);
                $this->session->set('user_role', $result['user']['role']);
                $this->session->set('last_activity', time());
                $this->session->set('fingerprint', $this->generateFingerprint($request)); # Add fingerprint

                $this->auditLogger->logRegistration($result['user']['id']);

                // SECURITY: Use safe redirect to prevent open redirect attacks
                $safeRedirect = $this->getSafeRedirect($request, $this->paths->urlFor('home'));

                return $this->redirect($safeRedirect);
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

    private function generateFingerprint(ServerRequestInterface $request): string
    {
        $sessionConfig = $this->container->get('config')['session'];
        $components = $sessionConfig['fingerprint']['components'] ?? ['user_agent'];
        $salt = $sessionConfig['fingerprint']['salt'] ?? '';

        $data = [];
        foreach ($components as $component) {
            if ($component === 'user_agent') {
                $data[] = $request->getHeaderLine('User-Agent');
            } elseif ($component === 'ip_subnet') {
                $ip = $request->getServerParams()['REMOTE_ADDR'] ?? '0.0.0.0';
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    $data[] = substr($ip, 0, strrpos($ip, '.'));
                }
                // Match middleware logic: ignore if not IPv4
            } elseif ($component === 'accept_language') {
                $data[] = $request->getHeaderLine('Accept-Language');
            }
        }
        $data[] = $salt;

        return hash('sha256', implode('|', $data));
    }
}
