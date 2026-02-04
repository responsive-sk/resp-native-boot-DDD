<?php

// src/Middleware/PjaxMiddleware.php

declare(strict_types=1);

namespace Blog\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class PjaxMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        // Spracuj request
        $response = $handler->handle($request);

        // Skontroluj či ide o PJAX request
        $isPjax = $request->getHeaderLine('X-PJAX') === 'true'
            || $request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';

        if (!$isPjax) {
            return $response;
        }

        // Získaj obsah
        $content = (string) $response->getBody();

        // Ak je to fragment request (?fragment=1)
        if ($request->getQueryParams()['fragment'] ?? false) {
            // Vráť len HTML fragment bez layoutu
            $fragment = $this->extractPjaxFragment($content);

            return $this->createPjaxResponse($fragment, $response);
        }

        // Normálny PJAX request - extrahuj title a content
        $data = $this->extractPjaxData($content);

        // Vráť JSON response s dátami
        return new \Nyholm\Psr7\Response(
            $response->getStatusCode(),
            [
                'Content-Type' => 'application/json',
                'X-PJAX' => 'true',
            ],
            json_encode([
                'title' => $data['title'],
                'content' => $data['content'],
                'url' => (string) $request->getUri(),
            ])
        );
    }

    private function extractPjaxFragment(string $html): string
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $xpath = new \DOMXPath($dom);

        // Hľadaj PJAX container
        $containers = $xpath->query('//main[@data-container]');
        if ($containers->length > 0) {
            return $dom->saveHTML($containers->item(0));
        }

        // Fallback: celý <main>
        $main = $xpath->query('//main');
        if ($main->length > 0) {
            return $dom->saveHTML($main->item(0));
        }

        return $html;
    }

    private function extractPjaxData(string $html): array
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);

        $xpath = new \DOMXPath($dom);

        // Extrahuj title
        $title = '';
        $titleNodes = $xpath->query('//title');
        if ($titleNodes->length > 0) {
            $title = $titleNodes->item(0)->textContent;
        }

        // Extrahuj hlavný obsah
        $content = '';
        $containers = $xpath->query('//main[@data-container]');
        if ($containers->length > 0) {
            $content = $dom->saveHTML($containers->item(0));
        } else {
            // Fallback
            $main = $xpath->query('//main');
            if ($main->length > 0) {
                $content = $dom->saveHTML($main->item(0));
            }
        }

        return [
            'title' => $title,
            'content' => $content,
        ];
    }

    private function createPjaxResponse(string $content, ResponseInterface $originalResponse): ResponseInterface
    {
        return new \Nyholm\Psr7\Response(
            $originalResponse->getStatusCode(),
            [
                'Content-Type' => 'text/html; charset=utf-8',
                'X-PJAX' => 'true',
            ],
            $content
        );
    }
}
