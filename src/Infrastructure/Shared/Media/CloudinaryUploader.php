<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Shared\Media;

use Cloudinary\Api\ApiResponse;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\CloudinaryConfiguration;
use Blog\Domain\Shared\Media\Exception\ImageUploadException;
use Blog\Domain\Shared\Media\Image;
use Blog\Domain\Shared\Media\ImageId;
use Blog\Domain\Shared\Media\ImageUploader;

class CloudinaryUploader implements ImageUploader
{
    private Cloudinary $cloudinary;
    private string $defaultFolder;

    public function __construct(array $config)
    {
        $this->cloudinary = new Cloudinary(
            CloudinaryConfiguration::fromArray([
                'cloud' => [
                    'cloud_name' => $config['cloud_name'],
                    'api_key' => $config['api_key'],
                    'api_secret' => $config['api_secret'],
                ],
                'url' => [
                    'secure' => true,
                ],
            ])
        );
        $this->defaultFolder = $config['default_folder'] ?? 'uploads';
    }

    public function upload(array $file, string $context): Image
    {
        $this->validateFile($file);

        try {
            $uploadResult = $this->cloudinary->uploadApi()->upload($file['tmp_name'], [
                'folder' => $this->defaultFolder,
                'resource_type' => 'auto',
                'quality' => 'auto:good',
                'format' => 'auto',
                'secure' => true,
                'public_id' => $this->generatePublicId($file['name']),
            ]);

            $image = $this->createImageFromUploadResult($uploadResult, $file, $context);

            // Generate variants
            $variants = $this->generateVariants($image);
            foreach ($variants as $name => $url) {
                $image->addVariant($name, $url);
            }

            return $image;
        } catch (\Exception $e) {
            throw new ImageUploadException('Failed to upload image: ' . $e->getMessage(), 0, $e);
        }
    }

    public function delete(ImageId $imageId): void
    {
        try {
            $this->cloudinary->uploadApi()->destroy($imageId->getValue());
        } catch (\Exception $e) {
            throw new ImageUploadException('Failed to delete image: ' . $e->getMessage(), 0, $e);
        }
    }

    public function generateVariants(Image $image): array
    {
        $variants = [];
        $publicId = $image->getId()->getValue();

        // Thumbnail variant
        $variants['thumbnail'] = $this->cloudinary->image($publicId)
            ->resize('fill', 300, 200)
            ->quality('auto')
            ->format('auto')
            ->secure(true)
            ->toUrl();

        // Medium variant
        $variants['medium'] = $this->cloudinary->image($publicId)
            ->resize('limit', 800, 600)
            ->quality('auto')
            ->format('auto')
            ->secure(true)
            ->toUrl();

        // Large variant
        $variants['large'] = $this->cloudinary->image($publicId)
            ->resize('limit', 1200, 800)
            ->quality('auto')
            ->format('auto')
            ->secure(true)
            ->toUrl();

        return $variants;
    }

    private function validateFile(array $file): void
    {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new ImageUploadException('Invalid file upload');
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes, true)) {
            throw new ImageUploadException('File type not allowed: ' . $file['type']);
        }

        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $maxSize) {
            throw new ImageUploadException('File too large: ' . $file['size'] . ' bytes');
        }
    }

    private function generatePublicId(string $filename): string
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $basename = pathinfo($filename, PATHINFO_FILENAME);
        $slug = $this->slugify($basename);

        return $slug . '_' . uniqid();
    }

    private function createImageFromUploadResult(
        ApiResponse $uploadResult,
        array $file,
        string $context
    ): Image {
        $imageId = new ImageId($uploadResult['public_id']);

        $image = new Image(
            $imageId,
            $uploadResult['public_id'],
            $file['name'],
            $uploadResult['resource_type'] . '/' . $uploadResult['format'],
            $uploadResult['bytes'],
            $uploadResult['secure_url'],
            $uploadResult['width'] ?? null,
            $uploadResult['height'] ?? null
        );

        $image->addContext($context);

        return $image;
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
}
