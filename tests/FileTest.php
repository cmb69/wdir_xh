<?php

namespace Wdir;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    private string $path;

    protected function setUp(): void
    {
        vfsStream::setup("test", null, [
            "foo.bar" => "foobar",
        ]);
        $this->path = vfsStream::url("test/foo.bar");
    }

    private function sut(): File
    {
        return new File($this->path);
    }

    public function testPathIsCorrect(): void
    {
        $this->assertEquals($this->path, $this->sut()->path());
    }

    public function testNameIsCorrect(): void
    {
        $this->assertEquals("foo.bar", $this->sut()->name());
    }

    public function testExtensionIsCorrect(): void
    {
        $this->assertEquals("bar", $this->sut()->extension());
    }

    public function testSizeIsCorrect(): void
    {
        $this->assertEquals(6, $this->sut()->size());
    }

    public function testModificationTimeIsCorrect(): void
    {
        $timestamp = strtotime("1970-01-02T10:17:36+00:00");
        touch($this->path, $timestamp);
        $this->assertEquals($timestamp, $this->sut()->mtime());
    }
}
