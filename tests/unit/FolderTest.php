<?php

/**
 * Testing the folder class.
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
 * Testing the folder class.
 *
 * @category Testing
 * @package  Wdir
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Wdir_XH
 */
class FolderTest extends PHPUnit_Framework_TestCase
{
    /**
     * The test subject.
     *
     * @var Wdir_Folder
     */
    protected $subject;

    /**
     * Sets up the test fixture.
     *
     * @return void
     *
     * @global array The configuration of the plugins.
     */
    public function setUp()
    {
        global $plugin_cf;

        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('test'));
        file_put_contents(vfsStream::url('test/foo.txt'), '***');
        touch(vfsStream::url('test/foo.txt'), 345678);
        file_put_contents(vfsStream::url('test/bar.txt'), '**');
        touch(vfsStream::url('test/bar.txt'), 234567);
        file_put_contents(vfsStream::url('test/baz.txt'), '*');
        touch(vfsStream::url('test/baz.txt'), 123456);
        touch(vfsStream::url('test/foo.bar'));

        $plugin_cf['wdir'] = array(
            'sort_column' => 'name',
            'sort_ascending' => 'true',
            'filter_regexp' => ''
        );

        $this->subject = new Wdir_Folder(vfsStream::url('test/'), '*.txt');
    }

    /**
     * Tests that two files are found.
     *
     * @return void
     */
    public function testTwoFilesAreFound()
    {
        $this->assertCount(3, $this->subject->getFiles());
    }

    /**
     * Tests that all findings are Wdir_File instances.
     *
     * @return void
     */
    public function testAllFindingsAreFileInstances()
    {
        $this->assertContainsOnlyInstancesOf(
            'Wdir_File', $this->subject->getFiles()
        );
    }

    /**
     * Tests that the files are sorted by name.
     *
     * @return void
     */
    public function testFilesAreSortedByName()
    {
        $files = $this->subject->getFiles();
        $this->assertEquals('bar.txt', $files[0]->getName());
    }

    /**
     * Tests that the files are sorted by size.
     *
     * @return void
     *
     * @global array The configuration of the plugins.
     */
    public function testFilesAreSortedBySize()
    {
        global $plugin_cf;

        $plugin_cf['wdir']['sort_column'] = 'size';
        $files = $this->subject->getFiles();
        $this->assertEquals('baz.txt', $files[0]->getName());
    }

    /**
     * Tests that the files are sorted by date.
     *
     * @return void
     *
     * @global array The configuration of the plugins.
     */
    public function testFilesAreSortedByDate()
    {
        global $plugin_cf;

        $plugin_cf['wdir']['sort_column'] = 'date';
        $files = $this->subject->getFiles();
        $this->assertEquals('baz.txt', $files[0]->getName());
    }

    /**
     * Tests that the files are sorted descending by name.
     *
     * @return void
     *
     * @global array The configuration of the plugins.
     */
    public function testFilesAreSortedDescendingByName()
    {
        global $plugin_cf;

        $plugin_cf['wdir']['sort_ascending'] = '';
        $files = $this->subject->getFiles();
        $this->assertEquals('bar.txt', $files[2]->getName());
    }

    /**
     * Tests the regexp filter.
     *
     * @return void
     *
     * @global array The configuration of the plugins.
     */
    public function testRegexpFilter()
    {
        global $plugin_cf;

        $plugin_cf['wdir']['filter_regexp'] = 'true';
        $subject = new Wdir_Folder(vfsStream::url('test/'), '/^foo/');
        $this->assertCount(2, $subject->getFiles());
    }
}

?>
