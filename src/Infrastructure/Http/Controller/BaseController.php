<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller;

use Blog\Core\UseCaseHandler;
use Blog\Security\AuthorizationService;
use Blog\Security\Exception\AuthenticationException;
use Blog\Security\Exception\AuthorizationException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class BaseController
{
    public function __construct(
        protected ContainerInterface $container,
        protected UseCaseHandler $useCaseHandler,
        protected AuthorizationService $authorization
    ) {
    }

    /**
     * Zjednodušené vykonanie use-case
     */
    protected function executeUseCase(
        ServerRequestInterface $request,
        object $useCase,
        array $mappingConfig,
        string $responseType = 'web'
    ) {
        return $this->useCaseHandler->execute(
            $request,
            $useCase,
            $mappingConfig,
            $responseType
        );
    }

    /**
     * JSON response helper
     */
    protected function jsonResponse($data, int $status = 200): ResponseInterface
    {
        return $this->useCaseHandler->createJsonResponse($data, $status);
    }

    /**
     * Alias for jsonResponse commonly used in API controllers
     */
    protected function json($data, int $status = 200): ResponseInterface
    {
        return $this->jsonResponse($data, $status);
    }

    /**
     * HTML response helper
     */
    protected function htmlResponse(string $html, int $status = 200): ResponseInterface
    {
        return $this->useCaseHandler->createHtmlResponse($html, $status);
    }

    /**
     * Get service from container
     */
    protected function get(string $id): mixed
    {
        return $this->container->get($id);
    }

    /**
     * Redirect helper
     */
    protected function redirect(string $url, int $status = 302): ResponseInterface
    {
        $factory = new \Nyholm\Psr7\Factory\Psr17Factory();
        $response = $factory->createResponse($status);

        return $response->withHeader('Location', $url);
    }

    /**
     * Get current authenticated user
     */
    protected function getCurrentUser(): ?array
    {
        return $this->authorization->getUser();
    }

    /**
     * Require authentication for an action
     */
    protected function requireAuth(): array|ResponseInterface|null
    {
        try {
            $this->authorization->requireAuth();

            return $this->authorization->getUser();
        } catch (AuthenticationException $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => 'Authentication required',
                'message' => $e->getMessage(),
            ], 401);
        }
    }

    /**
     * Check if current user can modify an article
     */
    protected function requireArticleOwnership(int $articleId): array|ResponseInterface|null
    {
        $user = $this->requireAuth();
        if ($user === null || $user instanceof ResponseInterface) {
            return $user;
        }

        // Get the article to check ownership
        $useCase = $this->useCaseHandler->get(\Blog\Application\Blog\GetArticleById::class);

        try {
            $data = $this->executeUseCase(
                new \Nyholm\Psr7\ServerRequest('GET', '/api/article/' . $articleId),
                $useCase,
                ['article_id' => $articleId],
                'api'
            );

            $article = $data['article'] ?? null;
            if ($article && isset($article['author_id']) && $article['author_id'] !== $user['id']) {
                return $this->jsonResponse([
                    'success' => false,
                    'error' => 'Access denied',
                    'message' => 'You can only modify your own articles',
                ], 403);
            }

            return $user;

        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => 'Article not found',
                'message' => 'The requested article does not exist',
            ], 404);
        }
    }

    /**
     * Check MARK role for web controllers
     */
    protected function requireMarkWeb(): ?array
    {
        try {
            $this->authorization->requireMark();

            return $this->authorization->getUser();
        } catch (AuthorizationException $e) {
            return null; // Controller will handle the response
        } catch (AuthenticationException $e) {
            return null; // Controller will handle the response
        }
    }

    /**
     * Check article ownership for web controllers
     */
    /**
     * Check article ownership for web controllers
     */
    protected function requireArticleOwnershipWeb(int $articleId): array|ResponseInterface|null
    {
        $user = $this->requireAuth();
        if ($user === null) {
            return $this->htmlResponse('Authentication required', 401);
        }

        // Get the article to check ownership
        $useCase = $this->useCaseHandler->get(\Blog\Application\Blog\GetArticleById::class);

        try {
            $data = $this->executeUseCase(
                new \Nyholm\Psr7\ServerRequest('GET', '/api/article/' . $articleId),
                $useCase,
                ['article_id' => $articleId],
                'api'
            );

            $article = $data['article'] ?? null;
            if ($article && isset($article['author_id']) && $article['author_id'] !== $user['id']) {
                return $this->htmlResponse('Access denied: You can only modify your own articles', 403);
            }

            return $user;

        } catch (\Exception $e) {
            return $this->htmlResponse('Article not found', 404);
        }
    }

    /**
     * Secure redirect that validates the URL to prevent open redirect attacks
     */
    protected function safeRedirect(string $url): ResponseInterface
    {
        // Parse the URL to validate it
        $parsedUrl = parse_url($url);

        // If parsing fails, redirect to home
        if ($parsedUrl === false) {
            return $this->redirect('/');
        }

        // Check if URL has a scheme (http, https, etc.)
        if (isset($parsedUrl['scheme'])) {
            // Only allow same-origin redirects or relative URLs
            $host = $parsedUrl['host'] ?? '';
            $serverHost = ''; // Do not trust HTTP_HOST header for security

            // If host is different, it's potentially malicious
            if ($host !== $serverHost && $host !== '') {
                return $this->redirect('/');
            }

            // Only allow http and https schemes
            if (!in_array($parsedUrl['scheme'], ['http', 'https'], true)) {
                return $this->redirect('/');
            }
        }

        // Allow relative URLs (no scheme and host)
        if (!isset($parsedUrl['scheme']) && !isset($parsedUrl['host'])) {
            return $this->redirect($url);
        }

        // For absolute URLs, ensure they're same-origin
        // Do not trust HTTP_HOST header - only allow relative URLs for security
        if (isset($parsedUrl['host'])) {
            return $this->redirect('/');
        }

        // Default to safe redirect
        return $this->redirect('/');
    }

    /**
     * Get safe redirect URL from query parameters
     */
    protected function getSafeRedirect(ServerRequestInterface $request, string $default = '/'): string
    {
        $redirect = $request->getQueryParams()['redirect'] ?? $default;

        // Validate the redirect URL
        $parsedUrl = parse_url($redirect);

        // If parsing fails, use default
        if ($parsedUrl === false) {
            return $default;
        }

        // Only allow relative URLs or same-origin URLs
        if (isset($parsedUrl['scheme']) || isset($parsedUrl['host'])) {
            // If it has scheme or host, reject for security (Host header can be spoofed)
            if (isset($parsedUrl['host'])) {
                return $default;
            }

            // Only allow http and https
            if (isset($parsedUrl['scheme']) && !in_array($parsedUrl['scheme'], ['http', 'https'], true)) {
                return $default;
            }
        }

        return $redirect;
    }
}
