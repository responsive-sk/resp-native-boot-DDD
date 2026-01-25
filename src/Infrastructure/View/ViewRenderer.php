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
    ) {}

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
}
