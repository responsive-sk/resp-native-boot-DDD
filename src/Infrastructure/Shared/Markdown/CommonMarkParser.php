<?php

// src/Infrastructure/Shared/Markdown/CommonMarkParser.php

declare(strict_types=1);

namespace Blog\Infrastructure\Shared\Markdown;

use Blog\Domain\Shared\Markdown\MarkdownParserInterface;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;

class CommonMarkParser implements MarkdownParserInterface
{
    private CommonMarkConverter $converter;

    public function __construct()
    {
        $environment = new Environment([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new FrontMatterExtension());

        $this->converter = new CommonMarkConverter([], $environment);
    }

    public function toHtml(string $markdown): string
    {
        $result = $this->converter->convert($markdown);

        if ($result instanceof RenderedContentWithFrontMatter) {
            return $result->getContent();
        }

        return (string) $result;
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
        $result = $this->converter->convert($markdown);

        if ($result instanceof RenderedContentWithFrontMatter) {
            return $result->getFrontMatter() ?? [];
        }

        return [];
    }

    public function sanitize(string $markdown): string
    {
        // CommonMark už má built-in sanitization
        return $markdown;
    }
}
