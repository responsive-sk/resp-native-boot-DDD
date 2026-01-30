<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Blog\ValueObject;

use Blog\Domain\Blog\ValueObject\Content;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ContentTest extends TestCase
{
    public function test_creates_valid_content(): void
    {
        $content = Content::fromString('Toto je obsah článku.');

        $this->assertInstanceOf(Content::class, $content);
        $this->assertSame('Toto je obsah článku.', $content->toString());
    }

    public function test_trims_whitespace(): void
    {
        $content = Content::fromString('  Toto je obsah článku.  ');

        $this->assertSame('Toto je obsah článku.', $content->toString());
    }

    public function test_throws_exception_for_empty_content(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Obsah nemôže byť prázdny');

        Content::fromString('');
    }

    public function test_throws_exception_for_too_short_content(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Obsah musí mať aspoň 10 znakov');

        Content::fromString('abc');
    }

    public function test_accepts_minimum_length(): void
    {
        $content = Content::fromString('1234567890');

        $this->assertSame('1234567890', $content->toString());
    }

    public function test_excerpt_returns_full_text_when_shorter_than_limit(): void
    {
        $content = Content::fromString('Krátky text.');

        $this->assertSame('Krátky text.', $content->excerpt(100));
    }

    public function test_excerpt_truncates_long_text(): void
    {
        $longText = str_repeat('Lorem ipsum dolor sit amet. ', 10);
        $content = Content::fromString($longText);

        $excerpt = $content->excerpt(50);

        $this->assertLessThanOrEqual(53, mb_strlen($excerpt)); // 50 + "..."
        $this->assertStringEndsWith('...', $excerpt);
    }

    public function test_word_count(): void
    {
        $content = Content::fromString('Toto je testovací obsah článku.');

        $this->assertSame(6, $content->wordCount());
    }

    public function test_equals_returns_true_for_same_value(): void
    {
        $content1 = Content::fromString('Toto je obsah článku.');
        $content2 = Content::fromString('Toto je obsah článku.');

        $this->assertTrue($content1->equals($content2));
    }

    public function test_equals_returns_false_for_different_value(): void
    {
        $content1 = Content::fromString('Toto je obsah článku.');
        $content2 = Content::fromString('Iný obsah článku.');

        $this->assertFalse($content1->equals($content2));
    }
}
