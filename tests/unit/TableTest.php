<?php

/**
 * Testing the table view.
 *
 * PHP version 5
 *
 * @category  Testing
 * @package   Wdir
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2014 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   SVN: $Id$
 * @link      http://3-magi.net/?CMSimple_XH/Wdir_XH
 */

require_once './vendor/autoload.php';
require_once '../../cmsimple/functions.php';
require_once './classes/Domain.php';
require_once './classes/Presentation.php';

use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStream;

/**
 * Testing the table view.
 *
 * @category Testing
 * @package  Wdir
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Wdir_XH
 */
class TableTest extends PHPUnit_Framework_TestCase
{
    /**
     * The path of the test folder.
     *
     * @var string
     */
    private $_path;

    /**
     * Sets up the test fixture.
     *
     * @return void
     *
     * @global array The localization of the plugin.
     */
    public function setUp()
    {
        global $pth, $plugin_tx;

        $plugin_tx['wdir'] = array(
            'label_name' => 'Name',
            'label_size' => 'Size',
            'label_modified' => 'Modified',
            'label_file' => 'File',
            'format_type' => '%s file',
            'format_date' => 'm/d/Y h:i a'
        );
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('test'));
        $this->_path = vfsStream::url('test');
        $pth['folder'] = array(
            'plugins' => $this->_path . '/',
            'userfiles' => $this->_path . '/'
        );
        mkdir($this->_path . '/downloads/', 0777);
        touch($this->_path . '/one.txt');
        touch($this->_path . '/two.pdf');
        touch($this->_path . '/three');
        mkdir($this->_path . '/wdir/images', 0777, true);
        touch($this->_path . '/wdir/images/file-txt.png');
    }

    /**
     * Tests that the table is rendered.
     *
     * @return void
     */
    public function testRendersTable()
    {
        $subject = new Wdir_Controller();
        $this->assertTag(
            array(
                'tag' => 'table',
                'attributes' => array('class' => 'wdir_table')
            ),
            $subject->renderTable('downloads')
        );
    }

    /**
     * Tests that a column heading is rendered.
     *
     * @param string $name A column name.
     *
     * @return void
     *
     * @dataProvider columnHeadingData
     */
    public function testRendersColumnHeading($name)
    {
        $subject = new Wdir_Controller();
        $this->assertTag(
            array(
                'tag' => 'tr',
                'child' => array(
                    'tag' => 'td',
                    'content' => $name
                ),
                'parent' => array('tag' => 'thead'),
                'ancestor' => array('tag' => 'table')
            ),
            $subject->renderTable('')
        );
    }

    /**
     * Provides data for testing the column headings.
     *
     * @return array
     */
    public function columnHeadingData()
    {
        return array(
            array('Name'),
            array('Size'),
            array('Modified')
        );
    }

    /**
     * Tests that three body rows are rendered.
     *
     * @return void
     */
    public function testRenders3BodyRows()
    {
        $subject = new Wdir_Controller();
        $this->assertTag(
            array(
                'tag' => 'tbody',
                'children' => array(
                    'count' => 3
                ),
                'parent' => array('tag' => 'table')
            ),
            $subject->renderTable('')
        );
    }

    /**
     * Tests that a cell is rendered.
     *
     * @param string $name  A column name.
     * @param string $value A cell value.
     *
     * @return void
     *
     * @dataProvider cellData
     */
    public function testRendersCell($name, $value)
    {
        $subject = new Wdir_Controller();
        $this->assertTag(
            array(
                'tag' => 'td',
                'attributes' => array('class' => 'wdir_' . $name),
                'content' => $value,
                'ancestor' => array('tag' => 'tbody')
            ),
            $subject->renderTable('')
        );
    }

    /**
     * Provides data for testing the cells.
     *
     * @return array
     */
    public function cellData()
    {
        return array(
            array('name', 'one.txt'),
            array('size', '0 KB')
        );
    }

    /**
     * Tests that the modified cell is rendered.
     *
     * @return void
     */
    public function testRendersModifiedCell()
    {
        $subject = new Wdir_Controller();
        $this->assertTag(
            array(
                'tag' => 'td',
                'attributes' => array('class' => 'wdir_modified'),
                'content' => date(
                    'm/d/Y h:i a', filemtime($this->_path . '/one.txt')
                ),
                'ancestor' => array('tag' => 'tbody')
            ),
            $subject->renderTable('')
        );
    }

    /**
     * Tests that the file icon is rendered.
     *
     * @return void
     *
     * @global array The paths of system files and folders.
     */
    public function testRendersFileIcon()
    {
        global $pth;

        $pth['folder']['plugins'] = './';
        $subject = new Wdir_Controller();
        $this->assertTag(
            array(
                'tag' => 'td',
                'attributes' => array('class' => 'wdir_name'),
                'child' => array(
                    'tag' => 'img',
                    'attributes' => array(
                        'src' => './wdir/images/file.png',
                        'alt' => 'File',
                        'title' => 'File'
                    )
                )
            ),
            $subject->renderTable('')
        );
    }

    /**
     * Tests that the filename is rendered as link.
     *
     * @return void
     */
    public function testRendersFilenameAsLink()
    {
        $subject = new Wdir_Controller();
        $this->assertTag(
            array(
                'tag' => 'td',
                'attributes' => array('class' => 'wdir_name'),
                'child' => array(
                    'tag' => 'a',
                    'attributes' => array(
                        'href' => $this->_path . '/one.txt',
                        'target' => '_blank'
                    )
                )
            ),
            $subject->renderTable('')
        );
    }
}

?>
