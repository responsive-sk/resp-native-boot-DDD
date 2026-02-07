<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\User\Entity;

use Blog\Domain\User\Entity\User;
use Blog\Domain\User\Event\UserRegisteredEvent;
use Blog\Domain\User\ValueObject\Email;
use Blog\Domain\User\ValueObject\HashedPassword;
use Blog\Domain\User\ValueObject\UserId;
use Blog\Domain\User\ValueObject\UserRole;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    private Email $email;
    private HashedPassword $password;
    private UserRole $userRole;

    protected function setUp(): void
    {
        $this->email = Email::fromString('test@example.com');
        $this->password = HashedPassword::fromPlainPassword('password123');
        $this->userRole = UserRole::user();
    }

    public function test_register_creates_user_with_generated_id(): void
    {
        $user = User::register($this->email, $this->password, $this->userRole);

        $this->assertInstanceOf(User::class, $user);
        $this->assertInstanceOf(UserId::class, $user->id());
        $this->assertSame($this->email, $user->email());
        $this->assertSame($this->password, $user->password());
        $this->assertSame($this->userRole, $user->role());
        $this->assertInstanceOf(DateTimeImmutable::class, $user->createdAt());
    }

    public function test_register_creates_user_with_default_user_role(): void
    {
        $user = User::register($this->email, $this->password);

        $this->assertTrue($user->role()->isUser());
        $this->assertFalse($user->role()->isMark());
    }

    public function test_register_records_user_registered_event(): void
    {
        $user = User::register($this->email, $this->password, $this->userRole);
        $events = $user->releaseEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserRegisteredEvent::class, $events[0]);
    }

    public function test_reconstitute_creates_user_from_existing_data(): void
    {
        $id = UserId::generate();
        $createdAt = new DateTimeImmutable('2023-01-01 00:00:00');

        $user = User::reconstitute($id, $this->email, $this->password, $this->userRole, $createdAt);

        $this->assertSame($id, $user->id());
        $this->assertSame($this->email, $user->email());
        $this->assertSame($this->password, $user->password());
        $this->assertSame($this->userRole, $user->role());
        $this->assertSame($createdAt, $user->createdAt());
    }

    public function test_reconstitute_does_not_record_events(): void
    {
        $id = UserId::generate();
        $createdAt = new DateTimeImmutable('2023-01-01 00:00:00');

        $user = User::reconstitute($id, $this->email, $this->password, $this->userRole, $createdAt);
        $events = $user->releaseEvents();

        $this->assertCount(0, $events);
    }

    public function test_verify_password_returns_true_for_correct_password(): void
    {
        $user = User::register($this->email, $this->password);

        $this->assertTrue($user->verifyPassword('password123'));
    }

    public function test_verify_password_returns_false_for_incorrect_password(): void
    {
        $user = User::register($this->email, $this->password);

        $this->assertFalse($user->verifyPassword('wrongpassword'));
    }

    public function test_change_password_updates_password(): void
    {
        $user = User::register($this->email, $this->password);
        $newPassword = HashedPassword::fromPlainPassword('newpassword123');

        $user->changePassword($newPassword);

        $this->assertSame($newPassword, $user->password());
        $this->assertTrue($user->verifyPassword('newpassword123'));
        $this->assertFalse($user->verifyPassword('password123'));
    }

    public function test_promote_to_mark_changes_role(): void
    {
        $user = User::register($this->email, $this->password, UserRole::user());

        $this->assertTrue($user->role()->isUser());
        $this->assertFalse($user->role()->isMark());

        $user->promoteToMark();

        $this->assertFalse($user->role()->isUser());
        $this->assertTrue($user->role()->isMark());
    }

    public function test_demote_to_user_changes_role(): void
    {
        $user = User::register($this->email, $this->password, UserRole::mark());

        $this->assertFalse($user->role()->isUser());
        $this->assertTrue($user->role()->isMark());

        $user->demoteToUser();

        $this->assertTrue($user->role()->isUser());
        $this->assertFalse($user->role()->isMark());
    }

    public function test_promote_to_mark_on_mark_user_has_no_effect(): void
    {
        $user = User::register($this->email, $this->password, UserRole::mark());

        $this->assertTrue($user->role()->isMark());

        $user->promoteToMark();

        $this->assertTrue($user->role()->isMark());
    }

    public function test_demote_to_user_on_user_role_has_no_effect(): void
    {
        $user = User::register($this->email, $this->password, UserRole::user());

        $this->assertTrue($user->role()->isUser());

        $user->demoteToUser();

        $this->assertTrue($user->role()->isUser());
    }

    public function test_id_throws_exception_for_null_id(): void
    {
        // This test ensures the id() method properly handles null case
        // Though with current implementation, this should never happen
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('User ID should not be null');

        // We need to create a user with null id to test this edge case
        // Since constructor is private, we'll use reflection for testing
        $reflection = new \ReflectionClass(User::class);
        $constructor = $reflection->getConstructor();
        $constructor->setAccessible(true);

        $user = $constructor->invokeArgs(
            null,
            [
                null, // null id
                $this->email,
                $this->password,
                $this->userRole,
                new DateTimeImmutable(),
            ]
        );

        $user->id();
    }

    public function test_release_events_clears_domain_events(): void
    {
        $user = User::register($this->email, $this->password, $this->userRole);

        $events1 = $user->releaseEvents();
        $events2 = $user->releaseEvents();

        $this->assertCount(1, $events1);
        $this->assertCount(0, $events2);
    }

    public function test_created_at_is_immutable(): void
    {
        $before = new DateTimeImmutable();
        $user = User::register($this->email, $this->password, $this->userRole);
        $after = new DateTimeImmutable();

        $this->assertGreaterThanOrEqual($before, $user->createdAt());
        $this->assertLessThanOrEqual($after, $user->createdAt());
    }

    public function test_getters_return_correct_types(): void
    {
        $user = User::register($this->email, $this->password, $this->userRole);

        $this->assertInstanceOf(UserId::class, $user->id());
        $this->assertInstanceOf(Email::class, $user->email());
        $this->assertInstanceOf(HashedPassword::class, $user->password());
        $this->assertInstanceOf(UserRole::class, $user->role());
        $this->assertInstanceOf(DateTimeImmutable::class, $user->createdAt());
    }
}
