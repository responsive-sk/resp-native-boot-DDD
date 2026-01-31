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
        // Get theme from environment (default: resp-front)
        $requestedTheme = $_ENV['THEME_NAME'] ?? 'resp-front';

        // Validate theme exists
        $this->theme = $this->findAvailableTheme($templatesPath, $requestedTheme);

        // Calculate theme path first
        $themePath = $templatesPath . '/' . $this->theme;

        // Initialize engine with theme path as base
        $this->plates = new Engine($themePath);

        // Register folders for namespaced templates using theme

        // Register theme folder with multiple aliases for compatibility
        $this->plates->addFolder($this->theme, $themePath);

        // Also register common aliases to prevent errors
        // This helps during migrations (e.g., boson -> resp-front)
        if ($this->theme === 'resp-front') {
            $this->plates->addFolder('boson', $themePath); // Backward compatibility
        }

        $this->plates->addFolder('partials', $themePath . '/partials');
        $this->plates->addFolder('layout', $themePath . '/layout');
        $this->plates->addFolder('mark', $themePath . '/mark');
        $this->plates->addFolder('error', $themePath . '/error');

        // Initialize route map
        $this->initializeRouteMap();

        // Register custom functions
        $this->registerFunctions();
    }

    /**
     * Find available theme with fallback support
     */
    private function findAvailableTheme(string $templatesPath, string $requestedTheme): string
    {
        $themePath = $templatesPath . '/' . $requestedTheme;

        // Check if requested theme exists
        if (is_dir($themePath)) {
            return $requestedTheme;
        }

        // Try fallback themes
        $fallbacks = ['resp-front', 'boson', 'default'];

        foreach ($fallbacks as $fallback) {
            $fallbackPath = $templatesPath . '/' . $fallback;
            if (is_dir($fallbackPath)) {
                // Log warning but continue
                error_log(sprintf(
                    'PlatesRenderer: Theme "%s" not found, using fallback "%s"',
                    $requestedTheme,
                    $fallback
                ));
                return $fallback;
            }
        }

        // If no theme found, throw helpful error
        $available = $this->listAvailableThemes($templatesPath);
        throw new \RuntimeException(sprintf(
            'PlatesRenderer: No theme found! Requested "%s", templates path: "%s". Available themes: %s. Please check THEME_NAME in .env or create symlink.',
            $requestedTheme,
            $templatesPath,
            empty($available) ? 'none' : implode(', ', $available)
        ));
    }

    /**
     * List available themes in templates directory
     */
    private function listAvailableThemes(string $templatesPath): array
    {
        if (!is_dir($templatesPath)) {
            return [];
        }

        $themes = [];
        $items = scandir($templatesPath);

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $fullPath = $templatesPath . '/' . $item;
            if (is_dir($fullPath)) {
                $themes[] = $item;
            }
        }

        return $themes;
    }

    private function initializeRouteMap(): void
    {
        // Map route names to paths
        $this->routeMap = [
            'home' => '/',
            'about' => '/about',
            'contact' => '/contact',

            // Public Blog
            'blog.index' => '/blog',
            'blog.show' => '/blog/{id}',
            'blog.show.slug' => '/blog/{slug}',
            'search.index' => '/search',

            // Auth
            'auth.login.form' => '/login',
            'auth.login' => '/login',
            'auth.register.form' => '/register',
            'auth.register' => '/register',
            'auth.logout' => '/logout',

            // Article Management (Legacy/Web user?)
            'article.create.form' => '/article/create',
            'article.store' => '/article/create',
            'article.edit' => '/article/{id}/edit',
            'article.update' => '/article/{id}/edit',
            'article.delete' => '/article/{id}/delete',

            // Mark Dashboard
            'mark.dashboard' => '/mark',
            'mark.dashboard.alias' => '/mark/dashboard',

            // Mark Articles
            'mark.articles.index' => '/mark/articles',
            'mark.articles.create' => '/mark/articles/create',
            'mark.articles.store' => '/mark/articles/create',
            'mark.articles.edit' => '/mark/articles/{id}/edit',
            'mark.articles.update' => '/mark/articles/{id}/edit',
            'mark.articles.delete' => '/mark/articles/{id}/delete',

            // Mark Users
            'mark.users.index' => '/mark/users',
            'mark.users.create' => '/mark/users/create',
            'mark.users.store' => '/mark/users/create',
            'mark.users.edit' => '/mark/users/{id}/edit',
            'mark.users.update' => '/mark/users/{id}/edit',
            'mark.users.delete' => '/mark/users/{id}/delete',
        ];
    }

    private function registerFunctions(): void
    {
        // Register escapeHtml as alias for e()
        $this->plates->registerFunction('escapeHtml', function ($string) {
            return htmlspecialchars((string) ($string ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
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
