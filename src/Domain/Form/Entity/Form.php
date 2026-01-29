<?php

declare(strict_types=1);

namespace Blog\Domain\Form\Entity;

use Blog\Domain\Form\ValueObject\FormId;
use DateTimeImmutable;

final class Form
{
    /**
     * @param array<int, mixed> $fields
     */
    private function __construct(
        private ?FormId $id,
        private string $title,
        private string $slug,
        private array $fields,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ) {
    }

    /**
     * @param array<int, mixed> $fields
     */
    public static function create(string $title, string $slug, array $fields): self
    {
        $now = new DateTimeImmutable();
        return new self(null, $title, $slug, $fields, $now, $now);
    }

    /**
     * @param array<int, mixed> $fields
     */
    public static function reconstitute(
        FormId $id,
        string $title,
        string $slug,
        array $fields,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt
    ): self {
        return new self($id, $title, $slug, $fields, $createdAt, $updatedAt);
    }

    public function id(): ?FormId
    {
        return $this->id;
    }

    public function setId(FormId $id): void
    {
        if ($this->id !== null) {
            throw new \LogicException('ID already set');
        }
        $this->id = $id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function fields(): array
    {
        return $this->fields;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
