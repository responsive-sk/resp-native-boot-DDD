<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Infrastructure\Paths as Paths;

final class PathsTest extends TestCase
{
    public function testJoin(): void
    {
        $this->assertSame('/foo/bar', Paths::join('/foo', 'bar'));
        $this->assertSame('/foo', Paths::join('/foo/', ''));
        $this->assertSame('/', Paths::join('', ''));
    }

    public function testPathHandlesAbsoluteUrlAndQuery(): void
    {
        $this->assertSame('https://example.com/a', Paths::path('https://example.com/a'));
        $this->assertSame('/articles?page=2', Paths::path('/articles', ['page' => 2]));
    }

    public function testBaseAndDataPathsAreStrings(): void
    {
        $this->assertIsString(Paths::basePath());
        $this->assertIsString(Paths::dataPath());
        $this->assertStringContainsString('data', Paths::dataPath());
    }
}
