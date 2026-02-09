<?php
// src/Domain/Form/ValueObject/FormId.php
declare(strict_types=1);

namespace Blog\Domain\Form\ValueObject;

use Blog\Domain\Shared\ValueObject\UuidValue;

final readonly class FormId extends UuidValue
{
    public static function generate(): static
    {
        return new static(parent::generate()->toBytes());
    }
}
