<?php

/**
 * Testing the file class.
 *
 * PHP version 5
 *
 * @category  Testing
 * @package   Wdir
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2015 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Wdir_XH
 */

namespace Wdir;

use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    protected File $subject;

    protected string $path;

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
