<?php
declare(strict_types=1);

namespace Blog\Domain\Image\Repository;

use Blog\Domain\Image\Entity\Image;
use Blog\Domain\Image\ValueObject\ImageId;

interface ImageRepositoryInterface
{
    public function findById(ImageId $id): ?Image;
    
    public function findByPublicId(string $publicId): ?Image;
    
    public function save(Image $image): void;
    
    public function delete(ImageId $id): bool;
    
    public function findByUploadedBy(\Blog\Domain\User\ValueObject\UserId $userId): array;
    
    public function findAll(int $limit = 50, int $offset = 0): array;
}
