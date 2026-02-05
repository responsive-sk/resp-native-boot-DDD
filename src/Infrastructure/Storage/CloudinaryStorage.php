<?php
declare(strict_types=1);

namespace Blog\Infrastructure\Storage;

use Blog\Domain\Image\Service\ImageStorageInterface;
use Cloudinary\Cloudinary;
use Cloudinary\Api\Upload\UploadApi;

class CloudinaryStorage implements ImageStorageInterface
{
    public function __construct(
        private Cloudinary $cloudinary,
        private array $config
    ) {}
    
    public function upload(\Psr\Http\Message\UploadedFileInterface $file, array $options = []): array
    {
        $uploadApi = $this->cloudinary->uploadApi();
        
        // Validate file
        $this->validateFile($file);
        
        // Upload to Cloudinary
        $result = $uploadApi->upload($file->getStream()->getContents(), [
            'public_id' => $options['public_id'] ?? null,
            'folder' => $options['folder'] ?? $this->config['default_folder'],
            'resource_type' => 'auto',
            'tags' => $options['tags'] ?? [],
            'context' => $options['context'] ?? [],
            'transformation' => $options['transformation'] ?? [],
            'quality' => $this->config['quality'] ?? 'auto:good',
        ]);
        
        return [
            'public_id' => $result['public_id'],
            'secure_url' => $result['secure_url'],
            'format' => $result['format'],
            'width' => $result['width'],
            'height' => $result['height'],
            'bytes' => $result['bytes'],
            'original_filename' => $result['original_filename'] ?? $result['public_id'],
            'tags' => $result['tags'] ?? [],
            'context' => $result['context'] ?? [],
            'exif' => $result['exif'] ?? [],
            'metadata' => [
                'signature' => $result['signature'],
                'resource_type' => $result['resource_type'],
                'version' => $result['version'],
                'type' => $result['type'],
                'created_at' => $result['created_at'],
            ]
        ];
    }
    
    public function delete(string $publicId): bool
    {
        try {
            $this->cloudinary->uploadApi()->destroy($publicId);
            return true;
        } catch (\Exception $e) {
            // Log error
            error_log('Failed to delete Cloudinary image: ' . $e->getMessage());
            return false;
        }
    }
    
    public function generateUrl(string $publicId, array $transformations = []): string
    {
        $image = $this->cloudinary->image($publicId);
        
        if ($this->config['secure'] ?? true) {
            $image->secure(true);
        }
        
        return $image->toUrl($transformations);
    }
    
    private function validateFile(\Psr\Http\Message\UploadedFileInterface $file): void
    {
        // Check file size
        $maxSize = $this->config['max_size'] ?? 5242880; // 5MB default
        if ($file->getSize() > $maxSize) {
            throw new \InvalidArgumentException('File size exceeds maximum allowed size');
        }
        
        // Check file type
        $allowedTypes = $this->config['allowed_types'] ?? ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($file->getClientMediaType(), $allowedTypes, true)) {
            throw new \InvalidArgumentException('File type not allowed');
        }
        
        // Check for upload errors
        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('File upload error: ' . $file->getError());
        }
    }
}
