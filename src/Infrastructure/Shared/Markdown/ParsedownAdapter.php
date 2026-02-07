<?php
// src/Infrastructure/Shared/Markdown/ParsedownAdapter.php - KOMPLETNÁ VERZIA

declare(strict_types=1);

namespace Blog\Infrastructure\Shared\Markdown;

use Blog\Domain\Shared\Markdown\MarkdownParserInterface;

class ParsedownAdapter implements MarkdownParserInterface
{
    private \ParsedownExtra $parser;

    public function __construct()
    {
        if (!class_exists('ParsedownExtra')) {
            throw new \RuntimeException(
                'ParsedownExtra class not found. Run: composer require erusev/parsedown-extra'
            );
        }
        
        $this->parser = new \ParsedownExtra();
        $this->parser->setSafeMode(true);
        $this->parser->setMarkupEscaped(true);
    }

    public function toHtml(string $markdown): string
    {
        return $this->parser->text($markdown);
    }

    public function toPlainText(string $markdown): string
    {
        $html = $this->toHtml($markdown);
        $plain = strip_tags($html);
        $plain = preg_replace('/\s+/', ' ', $plain);
        
        return trim($plain);
    }

    public function extractMetadata(string $markdown): array
    {
        $metadata = [];
        
        // Extract front matter (YAML style)
        if (preg_match('/^---\s*\n(.*?)\n---\s*\n/s', $markdown, $matches)) {
            $frontMatter = $matches[1];
            $lines = explode("\n", $frontMatter);
            
            foreach ($lines as $line) {
                if (preg_match('/^([a-zA-Z0-9_-]+):\s*(.+)$/', trim($line), $match)) {
                    $metadata[trim($match[1])] = trim($match[2]);
                }
            }
        }
        
        // Auto-extract headings for TOC
        if (preg_match_all('/^(#{1,6})\s+(.+)$/m', $markdown, $headings, PREG_SET_ORDER)) {
            $metadata['headings'] = array_map(function($heading) {
                return [
                    'level' => strlen($heading[1]),
                    'text' => $heading[2],
                    'slug' => $this->slugify($heading[2])
                ];
            }, $headings);
        }
        
        return $metadata;
    }

    private function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        
        return $text ?: 'n-a';
    }
}