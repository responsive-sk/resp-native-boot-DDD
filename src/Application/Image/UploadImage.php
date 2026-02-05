<?php
declare(strict_types=1);

namespace Blog\Application\Image;

use Blog\Domain\Image\Service\ImageUploaderInterface;
use Blog\Domain\User\Repository\UserRepositoryInterface;
use Blog\Domain\Image\Repository\ImageRepositoryInterface;
use Blog\Domain\Image\Factory\ImageFactory;

class UploadImage
{
    public function __construct(
        private ImageUploaderInterface $uploader,
        private UserRepositoryInterface $userRepository,
        private ImageFactory $imageFactory,
        private ImageRepositoryInterface $imageRepository
    ) {}
    
    public function __invoke(array $input): array
    {
        // Validate
        $this->validateInput($input);
        
        // Get user if provided
        $user = null;
        if (isset($input['user_id'])) {
            $user = $this->userRepository->findById($input['user_id']);
            if ($user === null) {
                throw new \InvalidArgumentException('User not found');
            }
        }
        
        // Upload to Cloudinary
        $uploadResult = $this->uploader->upload(
            $input['file'],
            [
                'folder' => $input['folder'] ?? 'blog_uploads',
                'tags' => $input['tags'] ?? [],
                'context' => [
                    'alt' => $input['alt_text'] ?? '',
                    'caption' => $input['caption'] ?? '',
                ]
            ]
        );
        
        // Create Image entity
        $image = $this->imageFactory->createFromCloudinaryResult(
            $uploadResult,
            $user?->getId()
        );
        
        // Save to repository
        $this->imageRepository->save($image);
        
        return [
            'success' => true,
            'image' => $image->toArray(),
            'urls' => [
                'original' => $image->getUrl(),
                'thumbnail' => $image->getThumbnailUrl(),
                'featured' => $image->getFeaturedUrl(),
            ]
        ];
    }
    
    private function validateInput(array $input): void
    {
        if (!isset($input['file'])) {
            throw new \InvalidArgumentException('File is required');
        }
        
        if (!$input['file'] instanceof \Psr\Http\Message\UploadedFileInterface) {
            throw new \InvalidArgumentException('File must be an uploaded file');
        }
    }
}
