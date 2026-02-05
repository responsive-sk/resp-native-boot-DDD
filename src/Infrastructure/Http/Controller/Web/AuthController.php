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

class AuthController extends BaseController
{
    public function __construct(
        private ViewRenderer $viewRenderer,
        private \ResponsiveSk\Slim4Paths\Paths $paths,
        private \Blog\Application\Audit\AuditLogger $auditLogger,
        private \ResponsiveSk\Slim4Session\SessionInterface $session,
        private \Blog\Domain\Blog\Repository\CategoryRepository $categoryRepository
    ) {
    }
    
    public function login(ServerRequestInterface $request): ResponseInterface
    {
        // GET request - zobraziť formulár
        if ($request->getMethod() === 'GET') {
            $categories = $this->categoryRepository->findAll();
            
            return $this->viewRenderer->renderResponse('auth/login', [
                'categories' => $categories,
                'title' => 'Login',
            ]);
        }
        
        // POST request - spracovať login
        try {
            $useCase = $this->useCaseHandler->get(LoginUser::class);
            
            $result = $this->executeUseCase($request, $useCase, [
                'email' => 'body:email',
                'password' => 'body:password',
            ], 'web');
            
            if ($result['success']) {
                // Nastav session
                $this->session->set('user_id', $result['user']['id']);
                $this->session->set('user_role', $result['user']['role']);
                
                // Audit log
                $this->auditLogger->logLogin($result['user']['id']);
                
                return $this->redirect($this->paths->urlFor('home'));
            }
            
            // Ak login zlyhal
            $categories = $this->categoryRepository->findAll();
            
            return $this->viewRenderer->renderResponse('auth/login', [
                'categories' => $categories,
                'title' => 'Login',
                'error' => 'Invalid credentials',
            ], 401);
            
        } catch (\DomainException $e) {
            $categories = $this->categoryRepository->findAll();
            
            return $this->viewRenderer->renderResponse('auth/login', [
                'categories' => $categories,
                'title' => 'Login',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
    
    public function register(ServerRequestInterface $request): ResponseInterface
    {
        // GET request - zobraziť formulár
        if ($request->getMethod() === 'GET') {
            $categories = $this->categoryRepository->findAll();
            
            return $this->viewRenderer->renderResponse('auth/register', [
                'categories' => $categories,
                'title' => 'Register',
            ]);
        }
        
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
            
            return $this->viewRenderer->renderResponse('auth/register', [
                'categories' => $this->categoryRepository->findAll(),
                'title' => 'Register',
                'error' => 'Registration failed',
            ], 400);
            
        } catch (\DomainException $e) {
            return $this->viewRenderer->renderResponse('auth/register', [
                'categories' => $this->categoryRepository->findAll(),
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