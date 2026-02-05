<?php
// src/Core/Application.php - UPRAVENÁ PRE DEBUGBAR

declare(strict_types=1);

namespace Blog\Core;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Application namespace for all use cases
 */
final class ApplicationConstants
{
    // Blog use cases
    public const BLOG_CREATE_ARTICLE = 'Blog\\Application\\Blog\\CreateArticle';
    public const BLOG_UPDATE_ARTICLE = 'Blog\\Application\\Blog\\UpdateArticle';
    public const BLOG_DELETE_ARTICLE = 'Blog\\Application\\Blog\\DeleteArticle';
    public const BLOG_GET_ALL_ARTICLES = 'Blog\\Application\\Blog\\GetAllArticles';
    public const BLOG_GET_ARTICLE_BY_SLUG = 'Blog\\Application\\Blog\\GetArticleBySlug';
    public const BLOG_SEARCH_ARTICLES = 'Blog\\Application\\Blog\\SearchArticles';
    
    // User use cases
    public const USER_LOGIN_USER = 'Blog\\Application\\User\\LoginUser';
    public const USER_REGISTER_USER = 'Blog\\Application\\User\\RegisterUser';
    public const USER_UPDATE_USER_ROLE = 'Blog\\Application\\User\\UpdateUserRole';
    
    // Image use cases
    public const IMAGE_UPLOAD_IMAGE = 'Blog\\Application\\Image\\UploadImage';
    public const IMAGE_DELETE_IMAGE = 'Blog\\Application\\Image\\DeleteImage';
    public const IMAGE_ATTACH_IMAGE_TO_ARTICLE = 'Blog\\Application\\Image\\AttachImageToArticle';
    
    // Form use cases
    public const FORM_CREATE_FORM = 'Blog\\Application\\Form\\CreateForm';
    public const FORM_GET_FORM = 'Blog\\Application\\Form\\GetForm';
    
    // Audit use cases
    public const AUDIT_LOGGER = 'Blog\\Application\\Audit\\AuditLogger';
}

final class Application implements RequestHandlerInterface
{
    private MiddlewareDispatcher $dispatcher;
    private ?\ResponsiveSk\PhpDebugBarMiddleware\DebugBarMiddleware $debugBarMiddleware = null;
    private array $additionalMiddlewares = [];
    
    /**
     * @param array<MiddlewareInterface> $middlewares
     */
    public function __construct(array $middlewares)
    {
        $this->dispatcher = new MiddlewareDispatcher($middlewares);
    }
    
    /**
     * Pridá middleware do aplikácie
     */
    public function add(MiddlewareInterface $middleware): void
    {
        $this->additionalMiddlewares[] = $middleware;
    }
    
    /**
     * Spracuje HTTP request
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Vytvor nový dispatcher s pridanými middlewares
        $allMiddlewares = array_merge($this->additionalMiddlewares, $this->dispatcher->getMiddlewares());
        $newDispatcher = new MiddlewareDispatcher($allMiddlewares);
        
        // Ak máme DebugBar, použime ho ako prvý
        if ($this->debugBarMiddleware !== null) {
            return $this->debugBarMiddleware->process($request, $newDispatcher);
        }
        
        // Normálne spracovanie
        return $newDispatcher->dispatch($request);
    }
    
    /**
     * Emituje HTTP response
     */
    public function emit(ResponseInterface $response): void
    {
        if (!headers_sent()) {
            http_response_code($response->getStatusCode());
            
            foreach ($response->getHeaders() as $name => $values) {
                foreach ($values as $value) {
                    header(sprintf('%s: %s', $name, $value), false);
                }
            }
        }
        
        echo (string) $response->getBody();
    }
}