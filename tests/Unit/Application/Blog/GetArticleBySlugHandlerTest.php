<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Blog;

use App\Application\Blog\GetArticleBySlug;
use App\Domain\Blog\Entity\Article;
use App\Domain\Blog\Repository\ArticleRepository;
use App\Domain\Blog\ValueObject\Slug;
use PHPUnit\Framework\TestCase;

final class GetArticleBySlugHandlerTest extends TestCase
{
    public function test_handle_returns_article_by_slug(): void
    {
        // Arrange
        $slug = Slug::fromString('test-article');
        $article = $this->createMock(Article::class);
        
        $repository = $this->createMock(ArticleRepository::class);
        $repository->expects($this->once())
            ->method('getBySlug')
            ->with($slug)
            ->willReturn($article);
        
        $handler = new GetArticleBySlug($repository);
        
        // Act
        $result = $handler->__invoke($slug);
        
        // Assert
        $this->assertSame($article, $result);
    }
    
    public function test_handle_returns_null_when_article_not_found(): void
    {
        // Arrange
        $slug = Slug::fromString('non-existent');
        
        $repository = $this->createMock(ArticleRepository::class);
        $repository->expects($this->once())
            ->method('getBySlug')
            ->with($slug)
            ->willReturn(null);
        
        $handler = new GetArticleBySlug($repository);
        
        // Act
        $result = $handler->__invoke($slug);
        
        // Assert
        $this->assertNull($result);
    }
}
