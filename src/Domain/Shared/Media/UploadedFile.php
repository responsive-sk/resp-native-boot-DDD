<?php

declare(strict_types=1);

namespace Blog\Domain\Shared\Media;

class UploadedFile
{
    private string $tmpName;
    private string $originalName;
    private string $mimeType;
    private int $size;
    private ?int $error;
    private ?string $fullPath;

    public function __construct(
        string $tmpName,
        string $originalName,
        string $mimeType,
        int $size,
        ?int $error = null,
        ?string $fullPath = null
    ) {
        $this->tmpName = $tmpName;
        $this->originalName = $originalName;
        $this->mimeType = $mimeType;
        $this->size = $size;
        $this->error = $error;
        $this->fullPath = $fullPath ?? $tmpName;
    }

    public function getTmpName(): string
    {
        return $this->tmpName;
    }

    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    public function getClientOriginalName(): string
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

    public function getError(): ?int
    {
        return $this->error;
    }

    public function getRealPath(): string
    {
        return $this->fullPath;
    }

    public function isValid(): bool
    {
        return $this->error === null || $this->error === UPLOAD_ERR_OK;
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mimeType, 'image/');
    }

    public function getExtension(): string
    {
        return pathinfo($this->originalName, PATHINFO_EXTENSION);
    }

    public function getBasename(): string
    {
        return pathinfo($this->originalName, PATHINFO_FILENAME);
    }

    public static function fromArray(array $file): self
    {
        return new self(
            $file['tmp_name'] ?? '',
            $file['name'] ?? '',
            $file['type'] ?? '',
            $file['size'] ?? 0,
            $file['error'] ?? null,
            $file['full_path'] ?? null
        );
    }
}
