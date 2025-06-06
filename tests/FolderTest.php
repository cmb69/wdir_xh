<?php

namespace Wdir;

use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class FolderTest extends TestCase
{
    private array $conf;

    protected function setUp(): void
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('test'));
        file_put_contents(vfsStream::url('test/foo.txt'), '***');
        touch(vfsStream::url('test/foo.txt'), 345678);
        file_put_contents(vfsStream::url('test/bar.txt'), '**');
        touch(vfsStream::url('test/bar.txt'), 234567);
        file_put_contents(vfsStream::url('test/Baz.txt'), '*');
        touch(vfsStream::url('test/Baz.txt'), 123456);
        touch(vfsStream::url('test/foo.bar'));

        $this->conf = XH_includeVar("./config/config.php", "plugin_cf")["wdir"];
    }

    private function sut(): Folder
    {
        return new Folder(vfsStream::url('test/'), '*.txt', "en", $this->conf);
    }

    public function testTwoFilesAreFound(): void
    {
        $this->assertCount(3, $this->sut()->getFiles());
    }

    public function testAllFindingsAreFileInstances(): void
    {
        $this->assertContainsOnlyInstancesOf(File::class, $this->sut()->getFiles());
    }

    /** @requires extension intl */
    public function testFilesAreSortedByName(): void
    {
        $files = $this->sut()->getFiles();
        $this->assertEquals('bar.txt', $files[0]->getName());
    }

    public function testFilesAreSortedBySize(): void
    {
        $this->conf["sort_column"] = "size";
        $files = $this->sut()->getFiles();
        $this->assertEquals('Baz.txt', $files[0]->getName());
    }

    public function testFilesAreSortedByDate(): void
    {
        $this->conf["sort_column"] = "date";
        $files = $this->sut()->getFiles();
        $this->assertEquals('Baz.txt', $files[0]->getName());
    }

    /** @requires extension intl */
    public function testFilesAreSortedDescendingByName(): void
    {
        $this->conf["sort_ascending"] = "";
        $files = $this->sut()->getFiles();
        $this->assertEquals('bar.txt', $files[2]->getName());
    }

    public function testSimpleFilter(): void
    {
        $this->conf["filter_regexp"] = "";
        $subject = new Folder(vfsStream::url('test/'), '?a?.txt', "en", $this->conf);
        $this->assertCount(2, $subject->getFiles());
        $subject = new Folder(vfsStream::url('test/'), 'foo.*', "en", $this->conf);
        $this->assertCount(2, $subject->getFiles());
    }

    public function testRegexpFilter(): void
    {
        $this->conf["filter_regexp"] = "true";
        $subject = new Folder(vfsStream::url('test/'), '/^foo/', "en", $this->conf);
        $this->assertCount(2, $subject->getFiles());
    }
}
