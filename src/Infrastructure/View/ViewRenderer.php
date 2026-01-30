<?php

// src/Infrastructure/View/ViewRenderer.php
declare(strict_types=1);

namespace Blog\Infrastructure\View;

use InvalidArgumentException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

final readonly class ViewRenderer
{
    public function __construct(
        private PlatesRenderer $plates,
        private array $pageConfig
    ) {
    }

    public function renderResponse(
        string $pageKey,
        array $data = [],
        int $status = 200
    ): ResponseInterface {
        if (!isset($this->pageConfig[$pageKey])) {
            throw new InvalidArgumentException("Page configuration not found for key: $pageKey");
        }

        $config = $this->pageConfig[$pageKey];
        $viewData = $this->prepareViewData($config, $data);
        $html = $this->plates->render($config['template'], $viewData);

        return new Response($status, ['Content-Type' => 'text/html'], $html);
    }

    private function prepareViewData(array $config, array $data): array
    {
        $viewData = array_merge([
            'title' => $config['title'],
            'description' => $config['description'],
        ], $data);

        // Handle dynamic titles/descriptions
        $item = $data['article'] ?? ($data['post'] ?? null);
        if ($item !== null) {
            $viewData['title'] = sprintf($config['title'], htmlspecialchars($item->title()->toString()));
            $viewData['description'] = sprintf($config['description'], htmlspecialchars($item->content()->excerpt(160)));
        }

        return $viewData;
    }

    // Simple render method
    public function render(string $template, array $data = []): string
    {
        return $this->plates->render($template, $data);
    }

    /**
     * Render error page based on HTTP status code
     * Falls back to generic error page if specific template doesn't exist
     */
    public function renderErrorResponse(
        int $statusCode,
        string $message = '',
        array $data = []
    ): ResponseInterface {
        // Try to find specific error template (e.g., error::404, error::500)
        $errorTemplate = "error::{$statusCode}";

        // Fallback chain: 404 -> 4xx -> error
        $fallbackTemplate = match (true) {
            $statusCode >= 400 && $statusCode < 500 => 'error::4xx',
            $statusCode >= 500 => 'error::5xx',
            default => 'error::error'
        };

        // Prepare error data
        $errorData = array_merge([
            'status_code' => $statusCode,
            'message' => $message ?: $this->getDefaultErrorMessage($statusCode),
            'title' => $this->getErrorTitle($statusCode),
        ], $data);

        // Try specific template first, then fallback
        try {
            $html = $this->plates->render($errorTemplate, $errorData);
        } catch (\Exception $e) {
            try {
                $html = $this->plates->render($fallbackTemplate, $errorData);
            } catch (\Exception $e2) {
                $html = $this->plates->render('error::error', $errorData);
            }
        }

        return new Response($statusCode, ['Content-Type' => 'text/html'], $html);
    }

    private function getDefaultErrorMessage(int $statusCode): string
    {
        return match ($statusCode) {
            404 => 'Stránka nebola nájdená',
            403 => 'Prístup zamietnutý',
            500 => 'Interná chyba servera',
            503 => 'Služba nie je dostupná',
            default => 'Vyskytla sa chyba'
        };
    }

    private function getErrorTitle(int $statusCode): string
    {
        return match ($statusCode) {
            404 => '404 - Stránka nenájdená',
            403 => '403 - Prístup zamietnutý',
            500 => '500 - Chyba servera',
            503 => '503 - Nedostupné',
            default => "Chyba {$statusCode}"
        };
    }
}
