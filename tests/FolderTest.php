<?php

namespace Wdir;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class FolderTest extends TestCase
{
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
        $this->conf = XH_includeVar("./config/config.php", "plugin_cf")["wdir"];
    }

    private function sut(): Folder
    {
        return new Folder(vfsStream::url('test/'), $this->conf);
    }

    public function testAllFindingsAreFileInstances(): void
    {
        $this->assertContainsOnlyInstancesOf(File::class, $this->sut()->getFiles());
    }
}
