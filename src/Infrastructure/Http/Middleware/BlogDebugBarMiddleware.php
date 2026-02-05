<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Blog\Infrastructure\DebugBar\BlogDebugBarStyles;

/**
 * Custom DebugBar middleware with blog branding
 */
class BlogDebugBarMiddleware implements \Psr\Http\Server\MiddlewareInterface
{
    private bool $enabled;
    private string $customCss;

    public function __construct()
    {
        $this->enabled = ($_ENV['APP_ENV'] ?? 'development') === 'development';
        $this->customCss = BlogDebugBarStyles::getCustomCss();
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$this->enabled) {
            return $handler->handle($request);
        }

        // Check if DebugBar is available
        if (!class_exists('\DebugBar\StandardDebugBar')) {
            return $handler->handle($request);
        }

        try {
            // Create DebugBar instance
            $debugBar = new \DebugBar\StandardDebugBar();

            // Add collectors
            $debugBar->addCollector(new \DebugBar\DataCollector\ConfigCollector([
                'custom_css' => $this->customCss
            ], 'blog_branding'));

            // Add memory collector
            $debugBar->addCollector(new \DebugBar\DataCollector\MemoryCollector());

            // Add request collector
            $debugBar->addCollector(new \DebugBar\DataCollector\RequestDataCollector());

            // Store DebugBar in request attributes
            $request = $request->withAttribute('debugbar', $debugBar);

            // Handle request
            $response = $handler->handle($request);

            // Inject DebugBar into response
            $response = $this->injectDebugBar($response, $debugBar);

            return $response;

        } catch (\Throwable $e) {
            error_log("BlogDebugBarMiddleware error: " . $e->getMessage());
            return $handler->handle($request);
        }
    }

    private function injectDebugBar(ResponseInterface $response, \DebugBar\StandardDebugBar $debugBar): ResponseInterface
    {
        $body = (string) $response->getBody();

        // Check if response is HTML
        $contentType = $response->getHeaderLine('Content-Type');
        if (!str_contains($contentType, 'text/html')) {
            return $response;
        }

        // Generate DebugBar HTML and CSS
        $debugBarRenderer = $debugBar->getJavascriptRenderer();
        $debugBarHtml = $debugBarRenderer->render();
        $debugBarHead = $debugBarRenderer->renderHead();

        // Inject custom CSS
        $customCssHtml = '<style>' . $this->customCss . '</style>';

        // Inject into HTML
        if (str_contains($body, '</head>')) {
            $body = str_replace('</head>', $customCssHtml . $debugBarHead . '</head>', $body);
        }

        if (str_contains($body, '</body>')) {
            $body = str_replace('</body>', $debugBarHtml . '</body>', $body);
        }

        // Create new response
        $responseFactory = new \Nyholm\Psr7\Factory\Psr17Factory();
        $newResponse = $responseFactory->createResponse($response->getStatusCode())
            ->withBody($responseFactory->createStream($body));

        // Copy headers
        foreach ($response->getHeaders() as $name => $values) {
            $newResponse = $newResponse->withHeader($name, $values);
        }

        return $newResponse;
    }
}
