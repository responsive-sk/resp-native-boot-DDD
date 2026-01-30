<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Blog\ValueObject;

use App\Domain\Blog\ValueObject\Slug;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class SlugTest extends TestCase
{
    public function test_normalizes_and_converts_to_string(): void
    {
        $slug = new Slug('Test--Slug 123!!');
        $this->assertSame('test-slug-123', $slug->toString());
    }

    public function test_empty_after_normalization_throws(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Slug('!!!'); // Special chars that get removed completely
    }

    public function test_from_string_factory_method(): void
    {
        $slug = Slug::fromString('Test String');
        $this->assertSame('test-string', $slug->toString());
    }

    public function test_constructor_normalization(): void
    {
        $slug = new Slug('  HELLO World 123  ');
        $this->assertSame('hello-world-123', $slug->toString());
    }

    public function test_diacritics_removal(): void
    {
        $slug = new Slug('Článok s diakritikou');
        $this->assertSame('clanok-s-diakritikou', $slug->toString());
    }
}
