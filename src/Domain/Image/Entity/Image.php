<?php
declare(strict_types=1);

namespace Blog\Domain\Image\Entity;

use Blog\Domain\Image\ValueObject\ImageId;
use Blog\Domain\Image\ValueObject\CloudinaryMetadata;
use Blog\Domain\User\ValueObject\UserId;

class Image
{
    public function __construct(
        private ImageId $id,
        private string $publicId,        // Cloudinary public_id
        private string $secureUrl,       // Cloudinary secure_url
        private string $originalFilename,
        private string $format,          // jpg, png, webp
        private int $size,               // bytes
        private int $width,
        private int $height,
        private CloudinaryMetadata $metadata,
        private ?UserId $uploadedBy = null,
        private \DateTimeImmutable $createdAt,
        private ?\DateTimeImmutable $updatedAt = null
    ) {}
    
    public function getId(): ImageId
    {
        return $this->id;
    }
    
    public function getPublicId(): string
    {
        return $this->publicId;
    }
    
    public function getSecureUrl(): string
    {
        return $this->secureUrl;
    }
    
    public function getOriginalFilename(): string
    {
        return $this->originalFilename;
    }
    
    public function getFormat(): string
    {
        return $this->format;
    }
    
    public function getSize(): int
    {
        return $this->size;
    }
    
    public function getWidth(): int
    {
        return $this->width;
    }
    
    public function getHeight(): int
    {
        return $this->height;
    }
    
    public function getMetadata(): CloudinaryMetadata
    {
        return $this->metadata;
    }
    
    public function getUploadedBy(): ?UserId
    {
        return $this->uploadedBy;
    }
    
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
    
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
    
    public function getUrl(string $transformation = null): string
    {
        if ($transformation === null) {
            return $this->secureUrl;
        }
        
        // Cloudinary auto-generates transformed URLs
        $parts = explode('/', $this->secureUrl);
        $filename = array_pop($parts);
        
        // Insert transformation
        $transformedFilename = str_replace(
            $this->publicId . '.' . $this->format,
            $this->publicId . '/' . $transformation . '.' . $this->format,
            $filename
        );
        
        $parts[] = $transformedFilename;
        return implode('/', $parts);
    }
    
    public function getThumbnailUrl(): string
    {
        return $this->getUrl('thumbnail');
    }
    
    public function getFeaturedUrl(): string
    {
        return $this->getUrl('featured');
    }
    
    public function getMediumUrl(): string
    {
        return $this->getUrl('medium');
    }
    
    public function getLargeUrl(): string
    {
        return $this->getUrl('large');
    }
    
    public function updateMetadata(CloudinaryMetadata $metadata): void
    {
        $this->metadata = $metadata;
        $this->updatedAt = new \DateTimeImmutable();
    }
    
    public function setUploadedBy(UserId $userId): void
    {
        $this->uploadedBy = $userId;
        $this->updatedAt = new \DateTimeImmutable();
    }
    
    public function toArray(): array
    {
        return [
            'id' => $this->id->toString(),
            'public_id' => $this->publicId,
            'secure_url' => $this->secureUrl,
            'original_filename' => $this->originalFilename,
            'format' => $this->format,
            'size' => $this->size,
            'width' => $this->width,
            'height' => $this->height,
            'metadata' => $this->metadata->toArray(),
            'uploaded_by' => $this->uploadedBy?->toString(),
            'created_at' => $this->createdAt->format(\DateTimeInterface::ATOM),
            'updated_at' => $this->updatedAt?->format(\DateTimeInterface::ATOM),
            'urls' => [
                'original' => $this->getUrl(),
                'thumbnail' => $this->getThumbnailUrl(),
                'medium' => $this->getMediumUrl(),
                'large' => $this->getLargeUrl(),
                'featured' => $this->getFeaturedUrl(),
            ]
        ];
    }
}
