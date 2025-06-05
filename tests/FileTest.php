<?php

namespace Wdir;

use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    private File $subject;

    private string $path;

    protected function setUp(): void
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('test'));
        $this->path = vfsStream::url('test/foo.bar');
        file_put_contents($this->path, 'foobar');
        $this->subject = new File($this->path);
    }

    public function testPathIsCorrect(): void
    {
        $this->assertEquals($this->path, $this->subject->getPath());
    }

    public function testNameIsCorrect(): void
    {
        $this->assertEquals('foo.bar', $this->subject->getName());
    }

    public function testExtensionIsCorrect(): void
    {
        $this->assertEquals('bar', $this->subject->getExtension());
    }

    public function testSizeIsCorrect(): void
    {
        $this->assertEquals(6, $this->subject->getSize());
    }

    public function testModificationTimeIsCorrect(): void
    {
        touch($this->path, 123456);
        $this->assertEquals(123456, $this->subject->getModificationTime());
    }
}
