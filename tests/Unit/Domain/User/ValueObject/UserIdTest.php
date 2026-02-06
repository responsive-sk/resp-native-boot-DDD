<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\User\ValueObject;

use Blog\Domain\User\ValueObject\UserId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class UserIdTest extends TestCase
{
    public function test_generate_creates_valid_uuid(): void
    {
        $userId = UserId::generate();

        $this->assertInstanceOf(UserId::class, $userId);
        $this->assertNotEmpty($userId->toString());
        $this->assertTrue(\Ramsey\Uuid\Uuid::isValid($userId->toString()));
    }

    public function test_from_string_creates_valid_user_id(): void
    {
        $uuidString = '123e4567-e89b-12d3-a456-426614174000';
        $userId = UserId::fromString($uuidString);

        $this->assertInstanceOf(UserId::class, $userId);
        $this->assertSame($uuidString, $userId->toString());
    }

    public function test_from_string_throws_exception_for_invalid_uuid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid User UUID: invalid-uuid');

        UserId::fromString('invalid-uuid');
    }

    public function test_from_string_throws_exception_for_empty_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid User UUID: ');

        UserId::fromString('');
    }

    public function test_from_bytes_creates_valid_user_id(): void
    {
        $uuidString = '123e4567-e89b-12d3-a456-426614174000';
        $userIdFromString = UserId::fromString($uuidString);
        $userIdFromBytes = UserId::fromBytes($userIdFromString->toBytes());

        $this->assertTrue($userIdFromString->equals($userIdFromBytes));
        $this->assertSame($uuidString, $userIdFromBytes->toString());
    }

    public function test_to_string_returns_uuid_string(): void
    {
        $uuidString = '123e4567-e89b-12d3-a456-426614174000';
        $userId = UserId::fromString($uuidString);

        $this->assertSame($uuidString, $userId->toString());
    }

    public function test_to_bytes_returns_uuid_bytes(): void
    {
        $uuidString = '123e4567-e89b-12d3-a456-426614174000';
        $userId = UserId::fromString($uuidString);
        $bytes = $userId->toBytes();

        $this->assertIsString($bytes);
        $this->assertSame(16, strlen($bytes)); // UUID bytes are always 16 bytes
    }

    public function test_equals_returns_true_for_same_uuid(): void
    {
        $uuidString = '123e4567-e89b-12d3-a456-426614174000';
        $userId1 = UserId::fromString($uuidString);
        $userId2 = UserId::fromString($uuidString);

        $this->assertTrue($userId1->equals($userId2));
    }

    public function test_equals_returns_false_for_different_uuid(): void
    {
        $userId1 = UserId::fromString('123e4567-e89b-12d3-a456-426614174000');
        $userId2 = UserId::fromString('123e4567-e89b-12d3-a456-426614174001');

        $this->assertFalse($userId1->equals($userId2));
    }

    public function test_equals_returns_false_for_different_instances(): void
    {
        $userId1 = UserId::generate();
        $userId2 = UserId::generate();

        $this->assertFalse($userId1->equals($userId2));
    }

    public function test_magic_to_string_returns_uuid_string(): void
    {
        $uuidString = '123e4567-e89b-12d3-a456-426614174000';
        $userId = UserId::fromString($uuidString);

        $this->assertSame($uuidString, (string) $userId);
    }

    public function test_generated_user_ids_are_unique(): void
    {
        $userId1 = UserId::generate();
        $userId2 = UserId::generate();
        $userId3 = UserId::generate();

        $this->assertFalse($userId1->equals($userId2));
        $this->assertFalse($userId2->equals($userId3));
        $this->assertFalse($userId1->equals($userId3));
    }

    public function test_from_string_with_version_4_uuid(): void
    {
        $uuidString = '550e8400-e29b-41d4-a716-446655440000'; // Version 4 UUID
        $userId = UserId::fromString($uuidString);

        $this->assertSame($uuidString, $userId->toString());
    }

    public function test_from_string_with_version_1_uuid(): void
    {
        $uuidString = '123e4567-e89b-11d3-a456-426614174000'; // Version 1 UUID
        $userId = UserId::fromString($uuidString);

        $this->assertSame($uuidString, $userId->toString());
    }
}
