<?php

declare(strict_types=1);

namespace Blog\Domain\Shared\Markdown;

use Blog\Domain\Shared\Markdown\Exception\InvalidMarkdownException;

class MarkdownContent
{
    private string $raw;
    private string $html;
    private string $plainText;
    private array $metadata = [];
    private ?string $excerpt = null;

    public function __construct(
        string $markdown,
        ?MarkdownParserInterface $parser = null
    ) {
        if (empty(trim($markdown))) {
            throw new InvalidMarkdownException('Markdown content cannot be empty');
        }

        $this->raw = $markdown;
        $parser = $parser ?? new \Blog\Domain\Shared\Markdown\DefaultMarkdownParser();

        $this->html = $parser->toHtml($markdown);
        $this->plainText = $parser->toPlainText($markdown);
        $this->metadata = $parser->extractMetadata($markdown);
        $this->excerpt = $this->generateExcerpt($this->plainText);
    }

    private function generateExcerpt(string $text, int $maxLength = 200): string
    {
        if (mb_strlen($text) <= $maxLength) {
            return $text;
        }

        $excerpt = mb_substr($text, 0, $maxLength);
        $lastSpace = mb_strrpos($excerpt, ' ');

        if ($lastSpace !== false) {
            $excerpt = mb_substr($excerpt, 0, $lastSpace);
        }

        return trim($excerpt) . '...';
    }

    public function getRaw(): string
    {
        return $this->raw;
    }

    public function getHtml(): string
    {
        return $this->html;
    }

    public function getPlainText(): string
    {
        return $this->plainText;
    }

    public function getExcerpt(?int $maxLength = null): string
    {
        if ($maxLength && $maxLength < mb_strlen($this->excerpt)) {
            return $this->generateExcerpt($this->plainText, $maxLength);
        }

        return $this->excerpt;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function hasMetadata(string $key): bool
    {
        return isset($this->metadata[$key]);
    }

    public function getMetadataValue(string $key): mixed
    {
        return $this->metadata[$key] ?? null;
    }

    public function __toString(): string
    {
        return $this->raw;
    }
}
