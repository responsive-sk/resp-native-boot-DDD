<?php

declare(strict_types=1);

namespace Blog\Domain\Shared\Media;

class Image
{
    private ImageId $id;
    private string $filename;
    private string $originalName;
    private string $mimeType;
    private int $size;
    private ?int $width;
    private ?int $height;
    private ?string $altText;
    private ?string $caption;
    private array $variants = [];
    private string $storagePath;
    private \DateTimeImmutable $createdAt;
    private array $contexts = []; // ['blog:article:123', 'docs:page:456']

    public function __construct(
        ImageId $id,
        string $filename,
        string $originalName,
        string $mimeType,
        int $size,
        string $storagePath,
        ?int $width = null,
        ?int $height = null,
        ?string $altText = null,
        ?string $caption = null
    ) {
        $this->id = $id;
        $this->filename = $filename;
        $this->originalName = $originalName;
        $this->mimeType = $mimeType;
        $this->size = $size;
        $this->storagePath = $storagePath;
        $this->width = $width;
        $this->height = $height;
        $this->altText = $altText;
        $this->caption = $caption;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function addContext(string $context): void
    {
        if (!in_array($context, $this->contexts, true)) {
            $this->contexts[] = $context;
        }
    }

    public function removeContext(string $context): void
    {
        $this->contexts = array_filter(
            $this->contexts,
            fn ($c) => $c !== $context
        );
    }

    public function isUsedInContext(string $context): bool
    {
        return in_array($context, $this->contexts, true);
    }

    public function isOrphaned(): bool
    {
        return empty($this->contexts);
    }

    public function getUrl(?string $variant = null): string
    {
        if ($variant && isset($this->variants[$variant])) {
            return $this->variants[$variant];
        }

        return $this->storagePath . '/' . $this->filename;
    }

    public function addVariant(string $name, string $url): void
    {
        $this->variants[$name] = $url;
    }

    public function getId(): ImageId
    {
        return $this->id;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getAltText(): ?string
    {
        return $this->altText;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function getStoragePath(): string
    {
        return $this->storagePath;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getContexts(): array
    {
        return $this->contexts;
    }

    public function getVariants(): array
    {
        return $this->variants;
    }
}
