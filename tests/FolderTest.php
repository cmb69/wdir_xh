<?php

namespace Wdir;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class FolderTest extends TestCase
{
    private string $filter;
    private array $conf;

    protected function setUp(): void
    {
        vfsStream::setup("test", null, [
            "foo.txt" => "***",
            "bar.txt" => "**",
            "Baz.txt" => "*",
            "foo.bar" => "",
        ]);
        touch(vfsStream::url('test/foo.txt'), strtotime("1970-01-05T00:01:18+00:00"));
        touch(vfsStream::url('test/bar.txt'), strtotime("1970-01-03T17:09:27+00:00"));
        touch(vfsStream::url('test/Baz.txt'), strtotime("1970-01-02T10:17:36+00:00"));
        $this->filter = "*.txt";
        $this->conf = XH_includeVar("./config/config.php", "plugin_cf")["wdir"];
    }

    private function sut(): Folder
    {
        return new Folder(vfsStream::url('test/'), $this->filter, "en", $this->conf);
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
        $this->filter = "?a?.txt";
        $this->conf["filter_regexp"] = "";
        $this->assertCount(2, $this->sut()->getFiles());
        $this->filter = "foo.*";
        $this->assertCount(2, $this->sut()->getFiles());
    }

    public function testRegexpFilter(): void
    {
        $this->filter = '/^foo/';
        $this->conf["filter_regexp"] = "true";
        $this->assertCount(2, $this->sut()->getFiles());
    }
}
