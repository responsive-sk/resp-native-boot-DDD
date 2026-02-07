<?php

declare(strict_types=1);

namespace Blog\Domain\Shared\Markdown;

use Blog\Infrastructure\Shared\Markdown\CommonMarkParser;

class DefaultMarkdownParser implements MarkdownParserInterface
{
    private MarkdownParserInterface $adapter;

    public function __construct()
    {
        $this->adapter = new CommonMarkParser();
    }

    public function toHtml(string $markdown): string
    {
        return $this->adapter->toHtml($markdown);
    }

    public function toPlainText(string $markdown): string
    {
        return $this->adapter->toPlainText($markdown);
    }

    public function extractMetadata(string $markdown): array
    {
        return $this->adapter->extractMetadata($markdown);
    }

    public function sanitize(string $markdown): string
    {
        return $this->adapter->sanitize($markdown);
    }
}
