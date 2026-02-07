<?php

declare(strict_types=1);

namespace Blog\Domain\Image\ValueObject;

class CloudinaryMetadata
{
    public function __construct(
        private string $publicId,
        private string $resourceType,    // image, video, raw
        private string $type,            // upload, private, authenticated
        private string $signature,
        private int $version,
        private array $tags = [],
        private array $context = [],     // alt, caption, etc.
        private array $exif = []
    ) {
    }

    public function getPublicId(): string
    {
        return $this->publicId;
    }

    public function getResourceType(): string
    {
        return $this->resourceType;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSignature(): string
    {
        return $this->signature;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function getExif(): array
    {
        return $this->exif;
    }

    public function getTransformationUrl(array $transformations = []): string
    {
        $params = [];
        foreach ($transformations as $key => $value) {
            $params[] = "{$key}_{$value}";
        }

        $transformation = implode(',', $params);

        return "https://res.cloudinary.com/{cloud_name}/{$this->resourceType}/upload/{$transformation}/{$this->publicId}";
    }

    public function toArray(): array
    {
        return [
            'public_id' => $this->publicId,
            'resource_type' => $this->resourceType,
            'type' => $this->type,
            'signature' => $this->signature,
            'version' => $this->version,
            'tags' => $this->tags,
            'context' => $this->context,
            'exif' => $this->exif,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['public_id'],
            $data['resource_type'],
            $data['type'],
            $data['signature'],
            $data['version'],
            $data['tags'] ?? [],
            $data['context'] ?? [],
            $data['exif'] ?? []
        );
    }
}
