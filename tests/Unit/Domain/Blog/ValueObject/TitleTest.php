<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Blog\ValueObject;

use Blog\Domain\Blog\ValueObject\Title;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class TitleTest extends TestCase
{
    public function test_creates_valid_title(): void
    {
        $title = Title::fromString('Môj článok');

        $this->assertInstanceOf(Title::class, $title);
        $this->assertSame('Môj článok', $title->toString());
    }

    public function test_trims_whitespace(): void
    {
        $title = Title::fromString('  Môj článok  ');

        $this->assertSame('Môj článok', $title->toString());
    }

    public function test_throws_exception_for_empty_title(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Titulok nemôže byť prázdny');

        Title::fromString('');
    }

    public function test_throws_exception_for_whitespace_only(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Titulok nemôže byť prázdny');

        Title::fromString('   ');
    }

    public function test_throws_exception_for_too_short_title(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Titulok musí mať aspoň 3 znaky');

        Title::fromString('ab');
    }

    public function test_throws_exception_for_too_long_title(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Titulok môže mať maximálne 255 znakov');

        Title::fromString(str_repeat('a', 256));
    }

    public function test_accepts_minimum_length(): void
    {
        $title = Title::fromString('abc');

        $this->assertSame('abc', $title->toString());
    }

    public function test_accepts_maximum_length(): void
    {
        $longTitle = str_repeat('a', 255);
        $title = Title::fromString($longTitle);

        $this->assertSame($longTitle, $title->toString());
    }

    public function test_equals_returns_true_for_same_value(): void
    {
        $title1 = Title::fromString('Môj článok');
        $title2 = Title::fromString('Môj článok');

        $this->assertTrue($title1->equals($title2));
    }

    public function test_equals_returns_false_for_different_value(): void
    {
        $title1 = Title::fromString('Môj článok');
        $title2 = Title::fromString('Iný článok');

        $this->assertFalse($title1->equals($title2));
    }

    public function test_to_string_magic_method(): void
    {
        $title = Title::fromString('Môj článok');

        $this->assertSame('Môj článok', (string) $title);
    }
}
