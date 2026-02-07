<?php

declare(strict_types=1);

namespace Blog\Domain\Blog\Entity;

use Blog\Domain\Blog\ValueObject\AuthorId;
use Blog\Domain\Blog\ValueObject\Email;
use Blog\Domain\Blog\ValueObject\Username;

final class Author
{
    private function __construct(
        private readonly AuthorId $id,
        private readonly Username $username,
        private readonly Email $email,
        private readonly string $role
    ) {
    }

    public static function create(
        AuthorId $id,
        Username $username,
        Email $email,
        string $role = 'author'
    ): self {
        return new self($id, $username, $email, $role);
    }

    public static function reconstitute(
        AuthorId $id,
        Username $username,
        Email $email,
        string $role
    ): self {
        return new self($id, $username, $email, $role);
    }

    public function id(): AuthorId
    {
        return $this->id;
    }

    public function username(): Username
    {
        return $this->username;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function role(): string
    {
        return $this->role;
    }

    public function displayName(): string
    {
        return $this->username->toString();
    }
}
