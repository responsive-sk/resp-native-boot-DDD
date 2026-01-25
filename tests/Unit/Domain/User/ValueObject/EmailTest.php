<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\User\ValueObject;

use App\Domain\User\ValueObject\Email;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    public function test_creates_valid_email(): void
    {
        $email = Email::fromString('user@example.com');

        $this->assertInstanceOf(Email::class, $email);
        $this->assertSame('user@example.com', $email->toString());
    }

    public function test_converts_to_lowercase(): void
    {
        $email = Email::fromString('User@Example.COM');

        $this->assertSame('user@example.com', $email->toString());
    }

    public function test_trims_whitespace(): void
    {
        $email = Email::fromString('  user@example.com  ');

        $this->assertSame('user@example.com', $email->toString());
    }

    public function test_throws_exception_for_empty_email(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Email nemôže byť prázdny');

        Email::fromString('');
    }

    public function test_throws_exception_for_invalid_email(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Neplatný email formát');

        Email::fromString('invalid-email');
    }

    public function test_throws_exception_for_email_without_at(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Email::fromString('userexample.com');
    }

    public function test_throws_exception_for_email_without_domain(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Email::fromString('user@');
    }

    public function test_domain_returns_correct_value(): void
    {
        $email = Email::fromString('user@example.com');

        $this->assertSame('example.com', $email->domain());
    }

    public function test_equals_returns_true_for_same_value(): void
    {
        $email1 = Email::fromString('user@example.com');
        $email2 = Email::fromString('user@example.com');

        $this->assertTrue($email1->equals($email2));
    }

    public function test_equals_returns_false_for_different_value(): void
    {
        $email1 = Email::fromString('user@example.com');
        $email2 = Email::fromString('other@example.com');

        $this->assertFalse($email1->equals($email2));
    }

    public function test_equals_is_case_insensitive(): void
    {
        $email1 = Email::fromString('User@Example.COM');
        $email2 = Email::fromString('user@example.com');

        $this->assertTrue($email1->equals($email2));
    }

    public function test_to_string_magic_method(): void
    {
        $email = Email::fromString('user@example.com');

        $this->assertSame('user@example.com', (string) $email);
    }
}
