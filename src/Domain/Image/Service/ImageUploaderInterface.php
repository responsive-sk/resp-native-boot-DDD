<?php

declare(strict_types=1);

namespace Blog\Domain\Image\Service;

interface ImageUploaderInterface
{
    public function upload(\Psr\Http\Message\UploadedFileInterface $file, array $options = []): array;
}
