<?php
// src/Domain/Image/ValueObject/ImageId.php
declare(strict_types=1);

namespace Blog\Domain\Image\ValueObject;

use Blog\Domain\Shared\ValueObject\UuidValue;

final readonly class ImageId extends UuidValue
{
    // Inherits all functionality from UuidValue
    // Can add image-specific validation if needed

    public static function generate(): static
    {
        return new static(parent::generate()->toBytes());
    }
}
