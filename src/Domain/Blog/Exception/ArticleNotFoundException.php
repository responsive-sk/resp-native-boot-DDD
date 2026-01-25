<?php

declare(strict_types=1);

namespace Blog\Domain\Blog\Exception;

use Blog\Domain\Blog\ValueObject\ArticleId;

final class ArticleNotFoundException extends \DomainException
{
    public static function withId(ArticleId $id): self
    {
        return new self(sprintf('Článok s ID %s nebol nájdený', $id->toInt()));
    }
}
