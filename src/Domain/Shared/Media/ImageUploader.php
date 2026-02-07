<?php

declare(strict_types=1);

namespace Blog\Domain\Shared\Media;

use Blog\Domain\Shared\Media\Exception\ImageUploadException;

interface ImageUploader
{
    /**
     * Upload an image and return the Image entity
     *
     * @param array $file The uploaded file data from $_FILES
     * @param string $context The context where the image will be used (e.g., 'blog:article:123')
     * @return Image
     * @throws ImageUploadException
     */
    public function upload(array $file, string $context): Image;

    /**
     * Delete an image from storage
     *
     * @param ImageId $imageId
     * @throws ImageUploadException
     */
    public function delete(ImageId $imageId): void;

    /**
     * Generate image variants (thumbnails, etc.)
     *
     * @param Image $image
     * @return array Array of variant URLs keyed by variant name
     */
    public function generateVariants(Image $image): array;
}
