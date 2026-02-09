<?php

// src/Middleware/HtmxMiddleware.php

declare(strict_types=1);

namespace Blog\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class HtmxMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        // Debug log
        error_log('HTMX Middleware: Processing request: ' . $_SERVER['REQUEST_URI']);
        error_log('HTMX Headers: ' . json_encode([
            'HX-Request' => $request->getHeaderLine('HX-Request'),
            'HX-Boosted' => $request->getHeaderLine('HX-Boosted'),
            'HX-Trigger' => $request->getHeaderLine('HX-Trigger'),
            'HX-Target' => $request->getHeaderLine('HX-Target'),
        ]));

        // Spracuj request
        $response = $handler->handle($request);

        // Skontroluj či ide o HTMX request
        $isHtmx = $request->getHeaderLine('HX-Request') === 'true'
            || $request->getHeaderLine('HX-Boosted') === 'true';

        error_log('HTMX Middleware: Is HTMX request: ' . ($isHtmx ? 'YES' : 'NO'));

        if (!$isHtmx) {
            error_log('HTMX Middleware: Not HTMX request, returning normal response');
            return $response;
        }

        error_log('HTMX Middleware: Processing HTMX request...');

        // Získaj obsah
        $content = (string) $response->getBody();

        // Ak je to fragment request (?fragment=1)
        if ($request->getQueryParams()['fragment'] ?? false) {
            // Vráť len HTML fragment bez layoutu
            $fragment = $this->extractHtmxFragment($content);

            return $this->createHtmxResponse($fragment, $response);
        }

        // Normálny HTMX request - vráť len content
        $data = $this->extractHtmxContent($content);

        // Vráť HTML response s contentom
        error_log('HTMX Middleware: Returning HTML response with content length: ' . strlen($data));
        
        return new \Nyholm\Psr7\Response(
            $response->getStatusCode(),
            [
                'Content-Type' => 'text/html; charset=utf-8',
                'HX-Trigger' => 'afterSwap',
            ],
            $data
        );
    }

    private function extractHtmxFragment(string $html): string
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $xpath = new \DOMXPath($dom);

        // Hľadaj HTMX container
        $containers = $xpath->query('//*[@data-pjax-container]');

        if ($containers->length > 0) {
            return $dom->saveHTML($containers->item(0));
        }

        // Fallback: hľadaj #pjax-container
        $containers = $xpath->query('//*[@id="pjax-container"]');

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

    private function extractHtmxContent(string $html): string
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);

        $xpath = new \DOMXPath($dom);

        // Extrahuj hlavný obsah
        $content = '';
        $containers = $xpath->query('//*[@data-pjax-container]');

        if ($containers->length > 0) {
            $content = $dom->saveHTML($containers->item(0));
        } else {
            // Hľadaj #pjax-container
            $containers = $xpath->query('//*[@id="pjax-container"]');

            if ($containers->length > 0) {
                $content = $dom->saveHTML($containers->item(0));
            } else {
                // Fallback: hľadaj main[data-container]
                $containers = $xpath->query('//main[@data-container]');

                if ($containers->length > 0) {
                    $content = $dom->saveHTML($containers->item(0));
                } else {
                    // Fallback: celý <main>
                    $main = $xpath->query('//main');

                    if ($main->length > 0) {
                        $content = $dom->saveHTML($main->item(0));
                    }
                }
            }
        }

        return $content;
    }

    private function createHtmxResponse(string $content, ResponseInterface $originalResponse): ResponseInterface
    {
        return new \Nyholm\Psr7\Response(
            $originalResponse->getStatusCode(),
            [
                'Content-Type' => 'text/html; charset=utf-8',
                'HX-Trigger' => 'afterSwap',
            ],
            $content
        );
    }
}
