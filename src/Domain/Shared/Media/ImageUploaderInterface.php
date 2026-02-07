<?php

declare(strict_types=1);

namespace Blog\Domain\Shared\Media;

interface ImageUploaderInterface
{
    public function upload(UploadedFile $file, array $options = []): Image;
    public function delete(Image $image): void;
    public function createVariants(Image $image, array $variants): array;
}
