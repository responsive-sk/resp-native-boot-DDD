<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Web;

use Blog\Application\Audit\AuditLogger;
use Blog\Application\User\LoginUser;
use Blog\Application\User\RegisterUser;
use Blog\Infrastructure\View\ViewRenderer;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ResponsiveSk\Slim4Paths\Paths;

final readonly class AuthController
{
    public function __construct(
        private LoginUser $loginUser,
        private RegisterUser $registerUser,
        private ViewRenderer $viewRenderer,
        private Paths $paths,
        private AuditLogger $auditLogger
    ) {
    }

    public function loginForm(ServerRequestInterface $request): ResponseInterface
    {
        return $this->viewRenderer->renderResponse('auth.login');
    }

    public function login(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();

        try {
            $emailString = $data['email'] ?? '';
            $password = $data['password'] ?? '';

            $user = ($this->loginUser)($emailString, $password);

            $this->setupUserSession($user, $request);

            // Log successful login
            $this->auditLogger->logAuthentication(
                'login_success',
                $emailString,
                true,
                $request,
                ['user_id' => $user->id()->toString()]
            );

            $redirect = $request->getQueryParams()['redirect'] ?? '/mark/dashboard';

            return new Response(302, ['Location' => $redirect]);
        } catch (\DomainException $e) {
            // Log failed login
            $this->auditLogger->logAuthentication(
                'login_failed',
                $data['email'] ?? '',
                false,
                $request,
                ['error' => $e->getMessage()]
            );

            return $this->viewRenderer->renderResponse('auth.login', [
                'error' => 'Nesprávne prihlasovacie údaje',
                'email' => $data['email'] ?? '',
            ]);
        }
    }

    public function registerForm(ServerRequestInterface $request): ResponseInterface
    {
        return $this->viewRenderer->renderResponse('auth.register');
    }

    public function register(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();

        $emailRaw = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $confirmPassword = $data['confirm_password'] ?? '';

        if ($password !== $confirmPassword) {
            return $this->viewRenderer->renderResponse('auth.register', [
                'error' => 'Heslá sa nezhodujú',
                'email' => $emailRaw,
            ]);
        }

        try {
            // RegisterUser teraz vracia priamo User entitu
            $user = ($this->registerUser)($emailRaw, $password);

            $this->setupUserSession($user, $request);

            // Log successful registration
            $this->auditLogger->logAuthentication(
                'registration',
                $emailRaw,
                true,
                $request,
                ['user_id' => $user->id()->toString()]
            );

            $redirect = $request->getQueryParams()['redirect'] ?? '/blog';

            return new Response(302, ['Location' => $redirect]);
        } catch (\DomainException $e) {
            // Log failed registration
            $this->auditLogger->logAuthentication(
                'registration',
                $emailRaw,
                false,
                $request,
                ['error' => $e->getMessage()]
            );

            return $this->viewRenderer->renderResponse('auth.register', [
                'error' => $e->getMessage(),
                'email' => $emailRaw,
            ]);
        }
    }

    public function logout(ServerRequestInterface $request): ResponseInterface
    {
        $userId = $_SESSION['user_id'] ?? null;
        $userEmail = $_SESSION['user_email'] ?? null;

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();

        // Log logout
        if ($userId && $userEmail) {
            $this->auditLogger->logAuthentication(
                'logout',
                $userEmail,
                true,
                $request,
                ['user_id' => $userId]
            );
        }

        return new Response(302, ['Location' => '/']);
    }

    private function setupUserSession(\Blog\Domain\User\Entity\User $user, ServerRequestInterface $request): void
    {
        // Session konfigurácia pomocou Paths
        $config = require $this->paths->getPath('config') . '/session.php';

        // ODSTRÁŇ TÚTO ČIARU - to je CELÝ problém!
        // session_regenerate_id(true);

        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Základné údaje
        $_SESSION['user_id'] = $user->id()->toString();
        $_SESSION['user_role'] = $user->role()->toString();
        $_SESSION['user_email'] = $user->email()->toString();
        $_SESSION['last_activity'] = time();

        // Mark špecifické
        if ($user->role()->isMark()) {
            $_SESSION['mark_session'] = true;
        }

        // Fingerprint
        if ($config['fingerprint']['enabled']) {
            $_SESSION['fingerprint'] = $this->generateFingerprint($request, $config);
        }

        // Session metadata
        $_SESSION['_meta'] = [
            'login_at' => date('c'),
            'ip' => $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown',
            'via' => 'web',
        ];
    }

    private function generateFingerprint(ServerRequestInterface $request, array $config): string
    {
        $data = [];

        foreach ($config['fingerprint']['components'] as $component) {
            if ($component === 'user_agent') {
                $data[] = $request->getHeaderLine('User-Agent');
            }
        }

        $data[] = $config['fingerprint']['salt'];

        return hash('sha256', implode('|', $data));
    }
}