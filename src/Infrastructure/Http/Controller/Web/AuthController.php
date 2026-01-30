<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Web;

use Blog\Application\User\LoginUser;
use Blog\Application\User\RegisterUser;
use Blog\Infrastructure\View\ViewRenderer;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class AuthController
{
    public function __construct(
        private LoginUser $loginUser,
        private RegisterUser $registerUser,
        private ViewRenderer $viewRenderer
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

            session_regenerate_id(true);
            $_SESSION['user_id'] = $user->id()->toString();
            $_SESSION['user_role'] = $user->role()->toString();
            $_SESSION['last_activity'] = time();

            $redirect = $request->getQueryParams()['redirect'] ?? '/mark/dashboard';
            return new Response(302, ['Location' => $redirect]);
        } catch (\DomainException $e) {
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
            $userId = ($this->registerUser)($emailRaw, $password);

            session_regenerate_id(true);
            $_SESSION['user_id'] = $userId->toString();
            $_SESSION['user_email'] = $emailRaw;

            $redirect = $request->getQueryParams()['redirect'] ?? '/blog';
            return new Response(302, ['Location' => $redirect]);
        } catch (\DomainException $e) {
            return $this->viewRenderer->renderResponse('auth.register', [
                'error' => $e->getMessage(),
                'email' => $emailRaw,
            ]);
        }
    }

    public function logout(ServerRequestInterface $request): ResponseInterface
    {
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

        return new Response(302, ['Location' => '/']);
    }
}
