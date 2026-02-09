<?php

declare(strict_types=1);

namespace Blog\Application\Image;

use Blog\Domain\Image\Repository\ImageRepositoryInterface;
use Blog\Domain\Image\Service\ImageStorageInterface;

class DeleteImage
{
    public function __construct(
        private ImageRepositoryInterface $imageRepository,
        private ImageStorageInterface $imageStorage
    ) {}

    public function __invoke(array $input): array
    {
        $image = $this->imageRepository->findById($input['image_id']);
        if ($image === null) {
            throw new \InvalidArgumentException('Image not found');
        }

        // Delete from Cloudinary
        $deleted = $this->imageStorage->delete($image->getPublicId());

        if (!$deleted) {
            throw new \RuntimeException('Failed to delete image from storage');
        }

        // Delete from repository
        $this->imageRepository->delete($image->getId());

        return [
            'success' => true,
            'message' => 'Image deleted successfully',
        ];
    }
}
