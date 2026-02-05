<?php
declare(strict_types=1);

namespace Blog\Application\Image;

use Blog\Domain\Blog\Repository\ArticleRepository;
use Blog\Domain\Image\Repository\ImageRepositoryInterface;

class AttachImageToArticle
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private ImageRepositoryInterface $imageRepository
    ) {}
    
    public function __invoke(array $input): array
    {
        $article = $this->articleRepository->getById($input['article_id']);
        if ($article === null) {
            throw new \InvalidArgumentException('Article not found');
        }
        
        $image = $this->imageRepository->findById($input['image_id']);
        if ($image === null) {
            throw new \InvalidArgumentException('Image not found');
        }
        
        if ($input['is_featured'] ?? false) {
            // TODO: Implement setFeaturedImage method in Article entity
            // $article->setFeaturedImage($image);
        } else {
            // TODO: Implement addImage method in Article entity
            // $article->addImage($image);
        }
        
        $this->articleRepository->update($article);
        
        return [
            'success' => true,
            'article_id' => $article->getId(),
            'image_id' => $image->getId(),
            'is_featured' => $input['is_featured'] ?? false,
            'image_url' => $image->getUrl(),
        ];
    }
}
