<?php

declare(strict_types=1);

namespace Blog\Domain\Shared\Markdown;

interface MarkdownParserInterface
{
    public function toHtml(string $markdown): string;
    public function toPlainText(string $markdown): string;
    public function extractMetadata(string $markdown): array;
}
