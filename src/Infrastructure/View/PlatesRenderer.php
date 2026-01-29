<?php

declare(strict_types=1);

namespace Blog\Infrastructure\View;

use League\Plates\Engine;

final class PlatesRenderer
{
    private Engine $plates;
    private array $routeMap = [];
    private string $theme;

    public function __construct(string $templatesPath)
    {
        // Get theme from environment (default: boson)
        $this->theme = $_ENV['THEME_NAME'] ?? 'boson';

        $this->plates = new Engine($templatesPath);

        // Register folders for namespaced templates using theme
        $themePath = $templatesPath . '/' . $this->theme;
        $this->plates->addFolder($this->theme, $themePath);
        $this->plates->addFolder('partials', $themePath . '/partials');
        $this->plates->addFolder('layout', $themePath . '/layout');
        $this->plates->addFolder('mark', $themePath . '/mark');
        $this->plates->addFolder('error', $themePath . '/error');

        // Initialize route map
        $this->initializeRouteMap();

        // Register custom functions
        $this->registerFunctions();
    }

    private function initializeRouteMap(): void
    {
        // Map route names to paths
        $this->routeMap = [
            'home' => '/',
            'about' => '/about',
            'contact' => '/contact',
            'blog.index' => '/blog',
            'blog_index' => '/blog',
            'blog_show' => '/blog/{id}',
            'blog_show_slug' => '/blog/{slug}',
            'search_index' => '/search',
            'login_form' => '/login',
            'login' => '/login',
            'register_form' => '/register',
            'register' => '/register',
            'admin_dashboard' => '/admin',
            'admin.posts' => '/admin/posts',
            'post_create_form' => '/post/create',
            'post_edit_form' => '/post/{id}/edit',
            'post_delete' => '/post/{id}/delete',
        ];
    }

    private function registerFunctions(): void
    {
        // Register escapeHtml as alias for e()
        $this->plates->registerFunction('escapeHtml', function ($string) {
            return htmlspecialchars($string ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        });

        // Register url() function for generating URLs from route names
        $this->plates->registerFunction('url', function (string $nameOrPath = '', array $params = []) {
            // If it's a known route name, use the mapped path
            if (isset($this->routeMap[$nameOrPath])) {
                $path = $this->routeMap[$nameOrPath];

                // Replace parameters in path
                foreach ($params as $key => $value) {
                    $path = str_replace('{' . $key . '}', (string) $value, $path);
                }

                return $path;
            }

            // Otherwise treat as direct path
            return '/' . ltrim($nameOrPath, '/');
        });

        // Register asset() function for asset URLs
        $this->plates->registerFunction('asset', function (string $path) {
            return '/' . ltrim($path, '/');
        });

        // Register route() function (alias for url)
        $this->plates->registerFunction('route', function (string $name, array $params = []) {
            // If it's a known route name, use the mapped path
            if (isset($this->routeMap[$name])) {
                $path = $this->routeMap[$name];

                // Replace parameters in path
                foreach ($params as $key => $value) {
                    $path = str_replace('{' . $key . '}', (string) $value, $path);
                }

                return $path;
            }

            // Otherwise treat as direct path
            return '/' . ltrim($name, '/');
        });
    }

    public function render(string $template, array $data = []): string
    {
        return $this->plates->render($template, $data);
    }

}
