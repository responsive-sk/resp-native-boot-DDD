<?php
declare(strict_types=1);

namespace Blog\Infrastructure\Image;

use Blog\Domain\Image\Service\ImageProcessorInterface;

class CloudinaryImageProcessor implements ImageProcessorInterface
{
    public function __construct(
        private \Cloudinary\Cloudinary $cloudinary,
        private array $transformations
    ) {}
    
    public function createThumbnail(string $imageUrl, string $size = 'thumbnail'): string
    {
        $publicId = $this->extractPublicId($imageUrl);
        $config = $this->transformations[$size] ?? $this->transformations['thumbnail'];
        
        return $this->cloudinary->image($publicId)
            ->resize($config['crop'] ?? 'fill', $config['width'], $config['height'])
            ->gravity($config['gravity'] ?? 'auto')
            ->quality($config['quality'] ?? 'auto:good')
            ->format($config['format'] ?? 'auto')
            ->toUrl();
    }
    
    public function optimize(string $imageUrl, array $options = []): string
    {
        $publicId = $this->extractPublicId($imageUrl);
        
        $image = $this->cloudinary->image($publicId)
            ->quality('auto:best')
            ->format('auto')
            ->fetchFormat('auto');
            
        // Apply additional optimizations
        if (isset($options['quality'])) {
            $image->quality($options['quality']);
        }
        
        if (isset($options['format'])) {
            $image->format($options['format']);
        }
        
        return $image->toUrl();
    }
    
    public function addWatermark(string $imageUrl, string $watermarkPublicId): string
    {
        $publicId = $this->extractPublicId($imageUrl);
        
        return $this->cloudinary->image($publicId)
            ->overlay($watermarkPublicId)
            ->gravity('south_east')
            ->x(10)
            ->y(10)
            ->width(0.1) // 10% of image
            ->opacity(50)
            ->toUrl();
    }
    
    private function extractPublicId(string $url): string
    {
        // Extract public_id from Cloudinary URL
        // Example: https://res.cloudinary.com/cloud_name/image/upload/v1234567890/folder/image.jpg
        // We need to extract: folder/image
        
        $parts = parse_url($url);
        if (!isset($parts['path'])) {
            throw new \InvalidArgumentException('Invalid Cloudinary URL');
        }
        
        $pathParts = explode('/', trim($parts['path'], '/'));
        
        // Remove version if present
        if (preg_match('/^v\d+$/', end($pathParts))) {
            array_pop($pathParts);
        }
        
        // Remove cloud_name and upload/image parts
        $skipParts = 3; // cloud_name, image/upload or video/upload
        $pathParts = array_slice($pathParts, $skipParts);
        
        // Remove file extension
        $filename = end($pathParts);
        $publicId = pathinfo($filename, PATHINFO_FILENAME);
        
        if (count($pathParts) > 1) {
            array_pop($pathParts);
            $folder = implode('/', $pathParts);
            return $folder . '/' . $publicId;
        }
        
        return $publicId;
    }
    
    public function generateTransformationUrl(string $publicId, array $transformations): string
    {
        $image = $this->cloudinary->image($publicId);
        
        foreach ($transformations as $key => $value) {
            switch ($key) {
                case 'width':
                case 'height':
                    $image->resize($transformations['crop'] ?? 'limit', $transformations['width'] ?? null, $transformations['height'] ?? null);
                    break;
                case 'quality':
                    $image->quality($value);
                    break;
                case 'format':
                    $image->format($value);
                    break;
                case 'gravity':
                    $image->gravity($value);
                    break;
                case 'crop':
                    // Handled together with width/height
                    break;
            }
        }
        
        return $image->toUrl();
    }
}
