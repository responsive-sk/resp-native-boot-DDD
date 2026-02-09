<?php
// src/Domain/Blog/ValueObject/AuthorId.php
declare(strict_types=1);

namespace Blog\Domain\Blog\ValueObject;

use Blog\Domain\Shared\ValueObject\UuidValue;
use InvalidArgumentException;

final readonly class AuthorId extends UuidValue
{
    // Dědí vše z UuidValue
    // Může přidat author-specific validaci pokud je potřeba
    
    public static function generate(): static
    {
        return new static(parent::generate()->toBytes());
    }
    
    public static function fromString(string $uuidString): static
    {
        // If it's a simple string ID (like "1", "2", "3"), convert it to a UUID
        if (ctype_digit($uuidString)) {
            // For simple numeric IDs, create a deterministic UUID based on the number
            // This allows compatibility with the sample data that uses simple IDs
            $numericId = (int)$uuidString;
            $uuidString = sprintf('%08x-%04x-%04x-%04x-%012x', 
                $numericId, 
                0, 
                0x4000, // UUID version 4
                0x8000, // UUID variant
                0 // rest as zeros for determinism
            );
        }
        
        return new static(parent::fromString($uuidString)->toBytes());
    }
    
    public static function fromUserId(\Blog\Domain\User\ValueObject\UserId $userId): static
    {
        return new static($userId->toBytes());
    }
    
    public function toUserId(): \Blog\Domain\User\ValueObject\UserId
    {
        return \Blog\Domain\User\ValueObject\UserId::fromBytes($this->toBytes());
    }
}