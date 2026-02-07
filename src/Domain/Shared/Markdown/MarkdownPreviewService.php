<?php

declare(strict_types=1);

namespace Blog\Domain\Shared\Markdown;

class MarkdownPreviewService
{
    private MarkdownParserInterface $parser;
    private int $previewLength;

    public function __construct(MarkdownParserInterface $parser, int $previewLength = 500)
    {
        $this->parser = $parser;
        $this->previewLength = $previewLength;
    }

    public function generatePreview(string $markdown): array
    {
        if (empty(trim($markdown))) {
            return [
                'html' => '',
                'plain_text' => '',
                'excerpt' => '',
                'metadata' => [],
                'word_count' => 0,
                'reading_time' => 0,
            ];
        }

        $sanitized = $this->parser->sanitize($markdown);
        $content = new MarkdownContent($sanitized, $this->parser);

        return [
            'html' => $content->getHtml(),
            'plain_text' => $content->getPlainText(),
            'excerpt' => $content->getExcerpt($this->previewLength),
            'metadata' => $content->getMetadata(),
            'headings' => $content->getMetadataValue('headings') ?? [],
            'word_count' => str_word_count($content->getPlainText()),
            'reading_time' => $this->calculateReadingTime($content->getPlainText()),
        ];
    }

    public function generateLivePreview(string $markdown): array
    {
        $preview = $this->generatePreview($markdown);

        // Add live-specific features
        $preview['is_valid'] = $this->isValidMarkdown($markdown);
        $preview['warnings'] = $this->getWarnings($markdown);
        $preview['suggestions'] = $this->getSuggestions($markdown);

        return $preview;
    }

    public function generateTableOfContents(string $markdown): array
    {
        $content = new MarkdownContent($markdown, $this->parser);
        $headings = $content->getMetadataValue('headings') ?? [];

        $toc = [];
        $stack = [];

        foreach ($headings as $heading) {
            $level = $heading['level'];
            $text = $heading['text'];
            $slug = $heading['slug'];

            $item = [
                'text' => $text,
                'slug' => $slug,
                'level' => $level,
                'children' => [],
            ];

            // Find parent in stack
            while (!empty($stack) && end($stack)['level'] >= $level) {
                array_pop($stack);
            }

            if (empty($stack)) {
                $toc[] = &$item;
            } else {
                $stack[count($stack) - 1]['children'][] = &$item;
            }

            $stack[] = $item;
            unset($item);
        }

        return $toc;
    }

    private function isValidMarkdown(string $markdown): bool
    {
        // Basic validation
        if (empty(trim($markdown))) {
            return false;
        }

        // Check for balanced brackets/parentheses
        $brackets = ['[' => ']', '(' => ')', '{' => '}'];
        $stack = [];

        for ($i = 0; $i < strlen($markdown); $i++) {
            $char = $markdown[$i];

            if (isset($brackets[$char])) {
                $stack[] = $brackets[$char];
            } elseif (in_array($char, $brackets)) {
                if (empty($stack) || array_pop($stack) !== $char) {
                    return false;
                }
            }
        }

        return empty($stack);
    }

    private function getWarnings(string $markdown): array
    {
        $warnings = [];

        // Check for potentially dangerous content
        if (preg_match('/<script\b[^>]*>/i', $markdown)) {
            $warnings[] = 'Script tags detected - will be sanitized';
        }

        if (preg_match('/javascript:/i', $markdown)) {
            $warnings[] = 'JavaScript URLs detected - will be sanitized';
        }

        // Check for broken links
        if (preg_match_all('/\[(.*?)\]\((.*?)\)/', $markdown, $matches)) {
            foreach ($matches[2] as $url) {
                if (empty(trim($url))) {
                    $warnings[] = 'Empty URL in markdown link';
                }
            }
        }

        return $warnings;
    }

    private function getSuggestions(string $markdown): array
    {
        $suggestions = [];

        // Check for missing alt text in images
        if (preg_match_all('/!\[.*?\]\(.*?\)/', $markdown, $matches)) {
            foreach ($matches[0] as $image) {
                if (preg_match('/!\[(.*?)\]/', $image, $altMatch)) {
                    if (empty(trim($altMatch[1]))) {
                        $suggestions[] = 'Consider adding alt text to images for accessibility';

                        break;
                    }
                }
            }
        }

        // Check for very long paragraphs
        $paragraphs = preg_split('/\n\n+/', $markdown);
        foreach ($paragraphs as $paragraph) {
            if (strlen($paragraph) > 1000) {
                $suggestions[] = 'Consider breaking long paragraphs into smaller ones';

                break;
            }
        }

        // Check for missing headings structure
        if (!preg_match('/^#{1,6}\s+/m', $markdown) && strlen($markdown) > 500) {
            $suggestions[] = 'Consider adding headings to improve structure';
        }

        return $suggestions;
    }

    private function calculateReadingTime(string $text): int
    {
        $wordCount = str_word_count($text);
        $wordsPerMinute = 200; // Average reading speed

        return max(1, (int) ceil($wordCount / $wordsPerMinute));
    }

    public function setPreviewLength(int $length): void
    {
        $this->previewLength = $length;
    }

    public function getPreviewLength(): int
    {
        return $this->previewLength;
    }
}
