<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Shared\Media;

use Blog\Domain\Shared\Media\Exception\ImageUploadException;
use Blog\Domain\Shared\Media\Image;
use Blog\Domain\Shared\Media\ImageId;

class FileStorage
{
    private string $storagePath;
    private array $allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];
    private int $maxSize;

    public function __construct(string $storagePath, int $maxSize = 5 * 1024 * 1024)
    {
        $this->storagePath = rtrim($storagePath, '/');
        $this->maxSize = $maxSize;

        if (!is_dir($this->storagePath)) {
            mkdir($this->storagePath, 0755, true);
        }
    }

    public function store(array $file, string $context): Image
    {
        $this->validateFile($file);

        $filename = $this->generateFilename($file['name']);
        $destination = $this->storagePath . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new ImageUploadException('Failed to move uploaded file');
        }

        $imageInfo = getimagesize($destination);
        if (!$imageInfo) {
            unlink($destination);

            throw new ImageUploadException('Invalid image file');
        }

        $imageId = new ImageId();
        $image = new Image(
            $imageId,
            $filename,
            $file['name'],
            $file['type'],
            $file['size'],
            $this->storagePath,
            $imageInfo[0],
            $imageInfo[1]
        );

        $image->addContext($context);

        return $image;
    }

    public function delete(ImageId $imageId, string $filename): void
    {
        $filePath = $this->storagePath . '/' . $filename;

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Also delete variants
        $variants = $this->findVariants($filename);
        foreach ($variants as $variant) {
            if (file_exists($variant)) {
                unlink($variant);
            }
        }
    }

    public function exists(string $filename): bool
    {
        return file_exists($this->storagePath . '/' . $filename);
    }

    public function getUrl(string $filename): string
    {
        return '/uploads/' . $filename;
    }

    public function getStoragePath(): string
    {
        return $this->storagePath;
    }

    private function validateFile(array $file): void
    {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new ImageUploadException('Invalid file upload');
        }

        if (!in_array($file['type'], $this->allowedTypes, true)) {
            throw new ImageUploadException('File type not allowed: ' . $file['type']);
        }

        if ($file['size'] > $this->maxSize) {
            throw new ImageUploadException('File too large: ' . $file['size'] . ' bytes');
        }

        $imageInfo = getimagesize($file['tmp_name']);
        if (!$imageInfo) {
            throw new ImageUploadException('Invalid image file');
        }
    }

    private function generateFilename(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $basename = pathinfo($originalName, PATHINFO_FILENAME);
        $slug = $this->slugify($basename);

        $filename = $slug . '_' . uniqid() . '.' . $extension;

        // Ensure unique filename
        $counter = 1;
        while ($this->exists($filename)) {
            $filename = $slug . '_' . uniqid() . '_' . $counter . '.' . $extension;
            $counter++;
        }

        return $filename;
    }

    private function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);

        return $text ?: 'image';
    }

    private function findVariants(string $filename): array
    {
        $basename = pathinfo($filename, PATHINFO_FILENAME);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $pattern = $this->storagePath . '/' . $basename . '_*.' . $extension;

        return glob($pattern);
    }

    public function cleanupOrphanedImages(): int
    {
        $files = glob($this->storagePath . '/*');
        $cleaned = 0;

        foreach ($files as $file) {
            if (is_file($file)) {
                // Check if file is older than 24 hours and not referenced
                $fileTime = filemtime($file);
                if ($fileTime < (time() - 86400)) {
                    unlink($file);
                    $cleaned++;
                }
            }
        }

        return $cleaned;
    }

    public function getStorageStats(): array
    {
        $files = glob($this->storagePath . '/*');
        $totalSize = 0;
        $fileCount = 0;
        $types = [];

        foreach ($files as $file) {
            if (is_file($file)) {
                $size = filesize($file);
                $totalSize += $size;
                $fileCount++;

                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $types[$extension] = ($types[$extension] ?? 0) + 1;
            }
        }

        return [
            'total_files' => $fileCount,
            'total_size' => $totalSize,
            'total_size_mb' => round($totalSize / 1024 / 1024, 2),
            'file_types' => $types,
        ];
    }
}
