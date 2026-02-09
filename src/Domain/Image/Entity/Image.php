<?php
// src/Domain/Image/Entity/Image.php
declare(strict_types=1);

namespace Blog\Domain\Image\Entity;

use Blog\Domain\Image\ValueObject\ImageId;
use Blog\Domain\User\ValueObject\UserId;
use Blog\Domain\Shared\ValueObject\DateTimeValue;
use DateTimeInterface;

final class Image
{
    private DateTimeValue $createdAt;
    private DateTimeValue $updatedAt;
    private int $width;
    private int $height;
    private int $size;
    private int $views = 0;
    private int $downloads = 0;

    public function __construct(
        private readonly ImageId $id,
        private string $filename,
        private string $originalName,
        private string $mimeType,
        private string $path,
        private UserId $uploadedBy,
        private ?string $altText = null,
        private ?string $caption = null,
        private ?string $credit = null,
        ?DateTimeInterface $createdAt = null
    ) {
        $this->createdAt = $createdAt
            ? DateTimeValue::fromString($createdAt->format('Y-m-d H:i:s'))
            : DateTimeValue::now();

        $this->updatedAt = clone $this->createdAt;

        // Extract basic info from path
        $this->extractImageInfo();
    }

    private function extractImageInfo(): void
    {
        // This would be populated by ImageProcessor
        // For now, set defaults
        $this->width = 0;
        $this->height = 0;
        $this->size = 0;

        if (file_exists($this->path)) {
            $this->size = filesize($this->path);

            $info = getimagesize($this->path);
            if ($info !== false) {
                $this->width = $info[0];
                $this->height = $info[1];
            }
        }
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

    public function getPath(): string
    {
        return $this->path;
    }

    public function updatePath(string $path): void
    {
        $this->path = $path;
        $this->updatedAt = DateTimeValue::now();
        $this->extractImageInfo();
    }

    public function getUploadedBy(): UserId
    {
        return $this->uploadedBy;
    }

    public function getAltText(): ?string
    {
        return $this->altText;
    }

    public function updateAltText(?string $altText): void
    {
        $this->altText = $altText;
        $this->updatedAt = DateTimeValue::now();
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function updateCaption(?string $caption): void
    {
        $this->caption = $caption;
        $this->updatedAt = DateTimeValue::now();
    }

    public function getCredit(): ?string
    {
        return $this->credit;
    }

    public function updateCredit(?string $credit): void
    {
        $this->credit = $credit;
        $this->updatedAt = DateTimeValue::now();
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getAspectRatio(): float
    {
        if ($this->height === 0) {
            return 0;
        }

        return $this->width / $this->height;
    }

    public function isPortrait(): bool
    {
        return $this->height > $this->width;
    }

    public function isLandscape(): bool
    {
        return $this->width > $this->height;
    }

    public function isSquare(): bool
    {
        return $this->width === $this->height;
    }

    public function incrementViews(): void
    {
        $this->views++;
        $this->updatedAt = DateTimeValue::now();
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function incrementDownloads(): void
    {
        $this->downloads++;
        $this->updatedAt = DateTimeValue::now();
    }

    public function getDownloads(): int
    {
        return $this->downloads;
    }

    public function getCreatedAt(): DateTimeValue
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeValue
    {
        return $this->updatedAt;
    }

    public function getExtension(): string
    {
        return pathinfo($this->filename, PATHINFO_EXTENSION);
    }

    public function getUrl(): string
    {
        // Generate URL based on storage strategy
        // This would be implemented by a StorageService
        return '/uploads/images/' . $this->filename;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->toString(),
            'filename' => $this->filename,
            'original_name' => $this->originalName,
            'mime_type' => $this->mimeType,
            'url' => $this->getUrl(),
            'alt_text' => $this->altText,
            'caption' => $this->caption,
            'credit' => $this->credit,
            'width' => $this->width,
            'height' => $this->height,
            'size' => $this->size,
            'aspect_ratio' => $this->getAspectRatio(),
            'orientation' => $this->isPortrait() ? 'portrait' : ($this->isLandscape() ? 'landscape' : 'square'),
            'views' => $this->views,
            'downloads' => $this->downloads,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
