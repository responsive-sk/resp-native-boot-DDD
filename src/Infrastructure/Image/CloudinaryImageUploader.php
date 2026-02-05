<?php
declare(strict_types=1);

namespace Blog\Infrastructure\Image;

use Blog\Domain\Image\Service\ImageUploaderInterface;
use Blog\Domain\Image\Service\ImageStorageInterface;

class CloudinaryImageUploader implements ImageUploaderInterface
{
    public function __construct(
        private ImageStorageInterface $storage,
        private array $config
    ) {}
    
    public function upload(\Psr\Http\Message\UploadedFileInterface $file, array $options = []): array
    {
        // Set default options
        $uploadOptions = array_merge([
            'folder' => $this->config['default_folder'] ?? 'blog_uploads',
            'tags' => [],
            'context' => [],
        ], $options);
        
        // Add automatic tags
        $uploadOptions['tags'] = array_merge(
            $uploadOptions['tags'],
            ['blog_upload', date('Y-m')]
        );
        
        return $this->storage->upload($file, $uploadOptions);
    }
}
