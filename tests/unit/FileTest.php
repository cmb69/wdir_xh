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

require_once './vendor/autoload.php';

use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStream;

/**
 * Testing the file class.
 *
 * @category Testing
 * @package  Wdir
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Wdir_XH
 */
class FileTest extends PHPUnit_Framework_TestCase
{
    /**
     * The test subect.
     *
     * @var Wdir_File
     */
    protected $subject;

    /**
     * The path of the test file.
     *
     * @var string
     */
    protected $path;

    /**
     * Sets up the test fixture.
     *
     * @return void
     */
    public function setUp()
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('test'));
        $this->path = vfsStream::url('test/foo.bar');
        file_put_contents($this->path, 'foobar');
        $this->subject = new Wdir_File($this->path);
    }

    /**
     * Tests that the path is correct.
     *
     * @return void
     */
    public function testPathIsCorrect()
    {
        $this->assertEquals($this->path, $this->subject->getPath());
    }

    /**
     * Tests that the name is correct.
     *
     * @return void
     */
    public function testNameIsCorrect()
    {
        $this->assertEquals('foo.bar', $this->subject->getName());
    }

    /**
     * Tests that the extension is correct.
     *
     * @return void
     */
    public function testExtensionIsCorrect()
    {
        $this->assertEquals('bar', $this->subject->getExtension());
    }

    /**
     * Tests that the size is correct.
     *
     * @return void
     */
    public function testSizeIsCorrect()
    {
        $this->assertEquals(6, $this->subject->getSize());
    }

    /**
     * Tests that the modification time is correct.
     *
     * @return void
     */
    public function testModificationTimeIsCorrect()
    {
        touch($this->path, 123456);
        $this->assertEquals(123456, $this->subject->getModificationTime());
    }
}

?>
