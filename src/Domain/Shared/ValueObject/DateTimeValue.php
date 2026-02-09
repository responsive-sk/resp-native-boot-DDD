<?php
// src/Domain/Shared/ValueObject/DateTimeValue.php
declare(strict_types=1);

namespace Blog\Domain\Shared\ValueObject;

use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;
use Stringable;

final readonly class DateTimeValue implements Stringable
{
    public function __construct(
        public DateTimeImmutable $value
    ) {
    }

    public static function now(): self
    {
        return new self(new DateTimeImmutable());
    }

    public static function fromString(string $date): self
    {
        try {
            return new self(new DateTimeImmutable($date));
        } catch (\Exception $e) {
            throw new InvalidArgumentException("Invalid date format: $date");
        }
    }

    public static function fromInterface(DateTimeInterface $date): self
    {
        return new self(DateTimeImmutable::createFromInterface($date));
    }

    public function format(string $format): string
    {
        return $this->value->format($format);
    }

    public function __toString(): string
    {
        return $this->value->format('Y-m-d H:i:s');
    }
}
