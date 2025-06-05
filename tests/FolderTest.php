<?php

namespace Wdir;

use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class FolderTest extends TestCase
{
    private Folder $subject;

    protected function setUp(): void
    {
        global $plugin_cf;

        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('test'));
        file_put_contents(vfsStream::url('test/foo.txt'), '***');
        touch(vfsStream::url('test/foo.txt'), 345678);
        file_put_contents(vfsStream::url('test/bar.txt'), '**');
        touch(vfsStream::url('test/bar.txt'), 234567);
        file_put_contents(vfsStream::url('test/Baz.txt'), '*');
        touch(vfsStream::url('test/Baz.txt'), 123456);
        touch(vfsStream::url('test/foo.bar'));

        $plugin_cf['wdir'] = array(
            'sort_column' => 'name',
            'sort_ascending' => 'true',
            'filter_regexp' => ''
        );

        $this->subject = new Folder(vfsStream::url('test/'), '*.txt');
    }

    public function testTwoFilesAreFound(): void
    {
        $this->assertCount(3, $this->subject->getFiles());
    }

    public function testAllFindingsAreFileInstances(): void
    {
        $this->assertContainsOnlyInstancesOf(File::class, $this->subject->getFiles());
    }

    public function testFilesAreSortedByName(): void
    {
        $files = $this->subject->getFiles();
        $this->assertEquals('Baz.txt', $files[0]->getName());
    }

    public function testFilesAreSortedByNameCaseInsensitive(): void
    {
        global $plugin_cf;

        $plugin_cf['wdir']['sort_column'] = 'name/i';
        $files = $this->subject->getFiles();
        $this->assertEquals('bar.txt', $files[0]->getName());
    }

    public function testFilesAreSortedBySize(): void
    {
        global $plugin_cf;

        $plugin_cf['wdir']['sort_column'] = 'size';
        $files = $this->subject->getFiles();
        $this->assertEquals('Baz.txt', $files[0]->getName());
    }

    public function testFilesAreSortedByDate(): void
    {
        global $plugin_cf;

        $plugin_cf['wdir']['sort_column'] = 'date';
        $files = $this->subject->getFiles();
        $this->assertEquals('Baz.txt', $files[0]->getName());
    }

    public function testFilesAreSortedDescendingByName(): void
    {
        global $plugin_cf;

        $plugin_cf['wdir']['sort_ascending'] = '';
        $files = $this->subject->getFiles();
        $this->assertEquals('Baz.txt', $files[2]->getName());
    }

    public function testSimpleFilter(): void
    {
        global $plugin_cf;

        $plugin_cf['wdir']['filter_regexp'] = '';
        $subject = new Folder(vfsStream::url('test/'), '?a?.txt');
        $this->assertCount(2, $subject->getFiles());
        $subject = new Folder(vfsStream::url('test/'), 'foo.*');
        $this->assertCount(2, $subject->getFiles());
    }

    public function testRegexpFilter(): void
    {
        global $plugin_cf;

        $plugin_cf['wdir']['filter_regexp'] = 'true';
        $subject = new Folder(vfsStream::url('test/'), '/^foo/');
        $this->assertCount(2, $subject->getFiles());
    }
}
