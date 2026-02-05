<?php
declare(strict_types=1);

namespace Blog\Infrastructure\Http\Controller\Api;

use Blog\Infrastructure\Http\Controller\BaseController;
use Blog\Application\Image\UploadImage;
use Blog\Application\Image\DeleteImage;
use Blog\Application\Image\AttachImageToArticle;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ImageController extends BaseController
{
    public function upload(ServerRequestInterface $request): ResponseInterface
    {
        $uploadedFiles = $request->getUploadedFiles();
        $file = $uploadedFiles['image'] ?? null;
        
        if (!$file) {
            return $this->jsonResponse([
                'success' => false,
                'error' => 'No image file provided'
            ], 400);
        }
        
        $useCase = new UploadImage(
            $this->useCaseHandler->get('image_uploader'),
            $this->useCaseHandler->get('user_repository'),
            $this->useCaseHandler->get('image_factory'),
            $this->useCaseHandler->get('image_repository')
        );
        
        try {
            $result = $this->executeUseCase($request, $useCase, [
                'file' => 'file:image',
                'folder' => 'body:folder',
                'alt_text' => 'body:alt_text',
                'caption' => 'body:caption',
                'tags' => 'body:tags',
                'user_id' => 'session:user_id'
            ], 'api');
            
            return $this->jsonResponse($result, 201);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
    
    public function delete(ServerRequestInterface $request, string $id): ResponseInterface
    {
        $useCase = new DeleteImage(
            $this->useCaseHandler->get('image_repository'),
            $this->useCaseHandler->get('image_storage')
        );
        
        try {
            $result = $this->executeUseCase($request, $useCase, [
                'image_id' => 'route:id',
                'user_id' => 'session:user_id'
            ], 'api');
            
            return $this->jsonResponse($result);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
    
    public function attachToArticle(ServerRequestInterface $request): ResponseInterface
    {
        $useCase = new AttachImageToArticle(
            $this->useCaseHandler->get('article_repository'),
            $this->useCaseHandler->get('image_repository')
        );
        
        try {
            $result = $this->executeUseCase($request, $useCase, [
                'article_id' => 'body:article_id',
                'image_id' => 'body:image_id',
                'is_featured' => 'body:is_featured'
            ], 'api');
            
            return $this->jsonResponse($result);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
    
    public function list(ServerRequestInterface $request): ResponseInterface
    {
        $query = $request->getQueryParams();
        
        try {
            // Cloudinary Admin API for listing
            $cloudinary = $this->useCaseHandler->get('cloudinary');
            $result = $cloudinary->adminApi()->assets([
                'max_results' => $query['limit'] ?? 50,
                'next_cursor' => $query['cursor'] ?? null,
                'tags' => $query['tags'] ?? false,
            ]);
            
            return $this->jsonResponse([
                'success' => true,
                'images' => $result['resources'],
                'pagination' => [
                    'next_cursor' => $result['next_cursor'] ?? null,
                    'total_count' => $result['total_count'] ?? 0,
                ]
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
