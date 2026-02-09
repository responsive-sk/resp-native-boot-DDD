<?php
declare(strict_types=1);

namespace Blog\Domain\Blog\Exception;

use Blog\Domain\Blog\ValueObject\ArticleId;
use Blog\Domain\Common\Exception\ResourceNotFoundException;

final class ArticleNotFoundException extends ResourceNotFoundException
{
    public static function withId(ArticleId $id): self
    {
        return new self(sprintf('Článok s ID %s nebol Nájdený', $id->toInt()));
    }
}
