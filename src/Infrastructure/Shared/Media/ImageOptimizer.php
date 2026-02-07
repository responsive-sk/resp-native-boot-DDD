<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Shared\Media;

use Blog\Domain\Shared\Media\Exception\ImageUploadException;
use Blog\Domain\Shared\Media\Image;

class ImageOptimizer
{
    private array $optimizations = [
        'thumbnail' => ['width' => 300, 'height' => 200, 'quality' => 80],
        'medium' => ['width' => 800, 'height' => 600, 'quality' => 85],
        'large' => ['width' => 1200, 'height' => 800, 'quality' => 90],
    ];

    public function optimizeImage(string $filePath, array $options = []): string
    {
        if (!extension_loaded('gd') && !extension_loaded('imagick')) {
            throw new ImageUploadException('No image processing extension available');
        }

        $imageInfo = getimagesize($filePath);
        if (!$imageInfo) {
            throw new ImageUploadException('Invalid image file');
        }

        $width = $options['width'] ?? $imageInfo[0];
        $height = $options['height'] ?? $imageInfo[1];
        $quality = $options['quality'] ?? 85;

        if (extension_loaded('imagick')) {
            return $this->optimizeWithImagick($filePath, $width, $height, $quality);
        } else {
            return $this->optimizeWithGD($filePath, $width, $height, $quality);
        }
    }

    public function generateOptimizedVariants(Image $image): array
    {
        $variants = [];
        $originalPath = $image->getStoragePath() . '/' . $image->getFilename();

        foreach ($this->optimizations as $name => $options) {
            try {
                $optimizedPath = $this->optimizeImage($originalPath, $options);
                $variants[$name] = $optimizedPath;
            } catch (ImageUploadException $e) {
                // Log error but continue with other variants
                error_log("Failed to generate {$name} variant: " . $e->getMessage());
            }
        }

        return $variants;
    }

    private function optimizeWithImagick(string $filePath, int $width, int $height, int $quality): string
    {
        $imagick = new \Imagick($filePath);

        $imagick->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1, true);
        $imagick->setImageCompressionQuality($quality);
        $imagick->stripImage();

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $outputPath = $this->generateOptimizedPath($filePath, $width, $height);

        $imagick->writeImage($outputPath);
        $imagick->destroy();

        return $outputPath;
    }

    private function optimizeWithGD(string $filePath, int $width, int $height, int $quality): string
    {
        $imageInfo = getimagesize($filePath);
        $mimeType = $imageInfo['mime'];

        $source = match ($mimeType) {
            'image/jpeg' => imagecreatefromjpeg($filePath),
            'image/png' => imagecreatefrompng($filePath),
            'image/gif' => imagecreatefromgif($filePath),
            'image/webp' => imagecreatefromwebp($filePath),
            default => throw new ImageUploadException('Unsupported image type: ' . $mimeType),
        };

        if (!$source) {
            throw new ImageUploadException('Failed to create image from file');
        }

        $originalWidth = imagesx($source);
        $originalHeight = imagesy($source);

        // Calculate new dimensions maintaining aspect ratio
        $ratio = min($width / $originalWidth, $height / $originalHeight);
        $newWidth = (int) ($originalWidth * $ratio);
        $newHeight = (int) ($originalHeight * $ratio);

        $destination = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG and GIF
        if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
            imagealphablending($destination, false);
            imagesavealpha($destination, true);
            $transparent = imagecolorallocatealpha($destination, 255, 255, 255, 127);
            imagefilledrectangle($destination, 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

        $outputPath = $this->generateOptimizedPath($filePath, $width, $height);

        match ($mimeType) {
            'image/jpeg' => imagejpeg($destination, $outputPath, $quality),
            'image/png' => imagepng($destination, $outputPath, (int) (9 - ($quality / 100) * 9)),
            'image/gif' => imagegif($destination, $outputPath),
            'image/webp' => imagewebp($destination, $outputPath, $quality),
            default => throw new ImageUploadException('Unsupported image type: ' . $mimeType),
        };

        imagedestroy($source);
        imagedestroy($destination);

        return $outputPath;
    }

    private function generateOptimizedPath(string $originalPath, int $width, int $height): string
    {
        $directory = dirname($originalPath);
        $filename = pathinfo($originalPath, PATHINFO_FILENAME);
        $extension = pathinfo($originalPath, PATHINFO_EXTENSION);

        return $directory . '/' . $filename . "_{$width}x{$height}." . $extension;
    }

    public function getImageInfo(string $filePath): array
    {
        $imageInfo = getimagesize($filePath);
        if (!$imageInfo) {
            throw new ImageUploadException('Invalid image file');
        }

        return [
            'width' => $imageInfo[0],
            'height' => $imageInfo[1],
            'type' => $imageInfo[2],
            'mime' => $imageInfo['mime'],
            'size' => filesize($filePath),
        ];
    }
}
