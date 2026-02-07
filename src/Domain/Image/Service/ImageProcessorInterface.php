<?php

declare(strict_types=1);

namespace Blog\Domain\Image\Service;

interface ImageProcessorInterface
{
    public function createThumbnail(string $imageUrl, string $size = 'thumbnail'): string;

    public function optimize(string $imageUrl, array $options = []): string;

    public function addWatermark(string $imageUrl, string $watermarkPublicId): string;
}
