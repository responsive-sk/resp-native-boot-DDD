<?php

declare(strict_types=1);

namespace Application\Shared;

use Domain\Shared\Markdown\Exception\InvalidMarkdownException;
use Domain\Shared\Markdown\MarkdownContent;
use Domain\Shared\Markdown\MarkdownParserInterface;

class ParseMarkdown
{
    private MarkdownParserInterface $parser;

    public function __construct(MarkdownParserInterface $parser)
    {
        $this->parser = $parser;
    }

    public function execute(string $markdown): MarkdownContent
    {
        if (empty(trim($markdown))) {
            throw new InvalidMarkdownException('Markdown content cannot be empty');
        }

        $sanitized = $this->parser->sanitize($markdown);

        return new MarkdownContent($sanitized, $this->parser);
    }

    public function executeWithPreview(string $markdown): array
    {
        if (empty(trim($markdown))) {
            throw new InvalidMarkdownException('Markdown content cannot be empty');
        }

        $sanitized = $this->parser->sanitize($markdown);
        $content = new MarkdownContent($sanitized, $this->parser);

        return [
            'content' => $content,
            'html' => $content->getHtml(),
            'plain_text' => $content->getPlainText(),
            'excerpt' => $content->getExcerpt(),
            'metadata' => $content->getMetadata(),
            'headings' => $content->getMetadataValue('headings') ?? [],
            'word_count' => str_word_count($content->getPlainText()),
            'reading_time' => $this->calculateReadingTime($content->getPlainText()),
        ];
    }

    public function validate(string $markdown): array
    {
        $errors = [];
        $warnings = [];

        if (empty(trim($markdown))) {
            $errors[] = 'Content cannot be empty';
        }

        // Check for potentially dangerous content
        if (preg_match('/<script\b[^>]*>/i', $markdown)) {
            $warnings[] = 'Script tags detected - will be sanitized';
        }

        if (preg_match('/javascript:/i', $markdown)) {
            $warnings[] = 'JavaScript URLs detected - will be sanitized';
        }

        // Check for common markdown issues
        if (preg_match_all('/\[(.*?)\]/', $markdown, $matches)) {
            foreach ($matches[1] as $linkText) {
                if (empty(trim($linkText))) {
                    $warnings[] = 'Empty link text found';
                }
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }

    private function calculateReadingTime(string $text): int
    {
        $wordCount = str_word_count($text);
        $wordsPerMinute = 200; // Average reading speed

        return max(1, (int) ceil($wordCount / $wordsPerMinute));
    }

    public function extractTableOfContents(string $markdown): array
    {
        $content = $this->execute($markdown);
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
}
