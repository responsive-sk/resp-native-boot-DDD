<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Shared\Media;

use Blog\Domain\Shared\Media\Exception\ImageUploadException;
use Blog\Domain\Shared\Media\Image;
use Blog\Domain\Shared\Media\ImageId;
use Blog\Domain\Shared\Media\ImageUploaderInterface;
use Blog\Domain\Shared\Media\UploadedFile;

class CloudinaryImageUploader implements ImageUploaderInterface
{
    private \Cloudinary\Cloudinary $cloudinary;
    private array $defaultOptions;

    public function __construct(
        string $cloudName,
        string $apiKey,
        string $apiSecret,
        array $defaultOptions = []
    ) {
        $this->cloudinary = new \Cloudinary\Cloudinary([
            'cloud' => [
                'cloud_name' => $cloudName,
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
            ],
        ]);

        $this->defaultOptions = array_merge([
            'folder' => 'shared_uploads',
            'resource_type' => 'image',
            'quality' => 'auto:good',
            'secure' => true,
            'format' => 'auto',
        ], $defaultOptions);
    }

    public function upload(UploadedFile $file, array $options = []): Image
    {
        $this->validateFile($file);

        try {
            $uploadOptions = array_merge($this->defaultOptions, $options);

            $result = $this->cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                $uploadOptions
            );

            // Create shared image entity
            $image = new Image(
                new ImageId($result['public_id']),
                $result['public_id'],
                $file->getClientOriginalName(),
                $file->getMimeType(),
                $result['bytes'],
                $result['secure_url'],
                $result['width'] ?? null,
                $result['height'] ?? null
            );

            // Add variants from derived transformations
            if (isset($result['derived']) && is_array($result['derived'])) {
                foreach ($result['derived'] as $derived) {
                    $transformation = $this->extractTransformationName($derived);
                    $image->addVariant($transformation, $derived['secure_url']);
                }
            }

            return $image;

        } catch (\Exception $e) {
            throw new ImageUploadException(
                sprintf('Failed to upload image: %s', $e->getMessage()),
                0,
                $e
            );
        }
    }

    public function delete(Image $image): void
    {
        try {
            // Check if image is used in other contexts
            if (!$image->isOrphaned()) {
                throw new ImageUploadException(
                    'Cannot delete image that is still referenced in content'
                );
            }

            $this->cloudinary->uploadApi()->destroy($image->getId()->getValue());

        } catch (\Exception $e) {
            throw new ImageUploadException(
                sprintf('Failed to delete image: %s', $e->getMessage()),
                0,
                $e
            );
        }
    }

    public function createVariants(Image $image, array $variants): array
    {
        $createdVariants = [];
        $publicId = $image->getId()->getValue();

        foreach ($variants as $name => $transformation) {
            try {
                $url = $this->cloudinary->image($publicId)
                    ->resize(
                        $transformation['crop'] ?? 'fill',
                        $transformation['width'] ?? 300,
                        $transformation['height'] ?? 200
                    )
                    ->quality($transformation['quality'] ?? 'auto')
                    ->format($transformation['format'] ?? 'auto')
                    ->secure(true)
                    ->toUrl();

                $image->addVariant($name, $url);
                $createdVariants[$name] = $url;

            } catch (\Exception $e) {
                // Log error but continue with other variants
                error_log("Failed to create variant {$name}: " . $e->getMessage());
            }
        }

        return $createdVariants;
    }

    private function validateFile(UploadedFile $file): void
    {
        if (!$file->isValid()) {
            throw new ImageUploadException('Invalid file upload');
        }

        if (!$file->isImage()) {
            throw new ImageUploadException('File is not an image: ' . $file->getMimeType());
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedTypes, true)) {
            throw new ImageUploadException('Image type not allowed: ' . $file->getMimeType());
        }

        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file->getSize() > $maxSize) {
            throw new ImageUploadException('File too large: ' . $file->getSize() . ' bytes');
        }
    }

    private function extractTransformationName(array $derived): string
    {
        // Extract transformation name from Cloudinary derived format
        if (isset($derived['transformation'])) {
            return is_array($derived['transformation'])
                ? json_encode($derived['transformation'])
                : (string) $derived['transformation'];
        }

        // Fallback to URL-based naming
        $url = $derived['secure_url'];
        if (preg_match('/w_(\d+)/', $url, $matches)) {
            return 'w_' . $matches[1];
        }

        if (preg_match('/c_(\w+)/', $url, $matches)) {
            return 'c_' . $matches[1];
        }

        return 'variant_' . uniqid();
    }

    public function generateDefaultVariants(Image $image): array
    {
        return $this->createVariants($image, [
            'thumbnail' => [
                'width' => 300,
                'height' => 200,
                'crop' => 'fill',
                'quality' => 80,
            ],
            'medium' => [
                'width' => 800,
                'height' => 600,
                'crop' => 'limit',
                'quality' => 85,
            ],
            'large' => [
                'width' => 1200,
                'height' => 800,
                'crop' => 'limit',
                'quality' => 90,
            ],
        ]);
    }
}
