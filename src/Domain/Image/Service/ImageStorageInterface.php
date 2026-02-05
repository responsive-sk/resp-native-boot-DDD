<?php
declare(strict_types=1);

namespace Blog\Domain\Image\Service;

interface ImageStorageInterface
{
    public function upload(\Psr\Http\Message\UploadedFileInterface $file, array $options = []): array;
    
    public function delete(string $publicId): bool;
    
    public function generateUrl(string $publicId, array $transformations = []): string;
}
