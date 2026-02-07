<?php

declare(strict_types=1);

namespace Blog\Domain\Image\Factory;

use Blog\Domain\Image\Entity\Image;
use Blog\Domain\Image\ValueObject\CloudinaryMetadata;
use Blog\Domain\Image\ValueObject\ImageId;
use Blog\Domain\User\ValueObject\UserId;

class ImageFactory
{
    public function createFromCloudinaryResult(array $uploadResult, ?UserId $uploadedBy = null): Image
    {
        $metadata = new CloudinaryMetadata(
            $uploadResult['public_id'],
            $uploadResult['metadata']['resource_type'],
            $uploadResult['metadata']['type'],
            $uploadResult['metadata']['signature'],
            $uploadResult['metadata']['version'],
            $uploadResult['tags'] ?? [],
            $uploadResult['context'] ?? [],
            $uploadResult['exif'] ?? []
        );

        return new Image(
            ImageId::generate(),
            $uploadResult['public_id'],
            $uploadResult['secure_url'],
            $uploadResult['original_filename'] ?? $uploadResult['public_id'],
            $uploadResult['format'],
            $uploadResult['bytes'],
            $uploadResult['width'],
            $uploadResult['height'],
            $metadata,
            $uploadedBy,
            new \DateTimeImmutable()
        );
    }

    public function createFromArray(array $data): Image
    {
        $metadata = CloudinaryMetadata::fromArray($data['metadata']);

        return new Image(
            ImageId::fromString($data['id']),
            $data['public_id'],
            $data['secure_url'],
            $data['original_filename'],
            $data['format'],
            $data['size'],
            $data['width'],
            $data['height'],
            $metadata,
            isset($data['uploaded_by']) ? UserId::fromString($data['uploaded_by']) : null,
            new \DateTimeImmutable($data['created_at']),
            isset($data['updated_at']) ? new \DateTimeImmutable($data['updated_at']) : null
        );
    }
}
