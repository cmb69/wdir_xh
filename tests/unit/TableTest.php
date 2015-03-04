<?php

/**
 * Testing the table view.
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
require_once '../../cmsimple/functions.php';

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
    protected $path;

    /**
     * Sets up the test fixture.
     *
     * @return void
     *
     * @global array The localization of the plugin.
     */
    public function setUp()
    {
        global $pth, $plugin_cf, $plugin_tx;

        $plugin_cf['wdir'] = array(
            'sort_column' => 'name',
            'sort_ascending' => 'true',
            'filter_regexp' => ''
        );
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
        $this->path = vfsStream::url('test');
        $pth['folder'] = array(
            'plugins' => $this->path . '/',
            'userfiles' => $this->path . '/'
        );
        mkdir($this->path . '/downloads/', 0777);
        touch($this->path . '/one.txt');
        touch($this->path . '/two.pdf');
        touch($this->path . '/three');
        mkdir($this->path . '/wdir/images', 0777, true);
        touch($this->path . '/wdir/images/file-txt.png');
    }

    /**
     * Tests that the JS configuration is written to $bjs.
     *
     * @return void
     *
     * @global string The (X)HTML fragment to insert at the bottom of the body.
     */
    public function testJSConfigurationIsWrittenToBJS()
    {
        global $bjs;

        $subject = new Wdir_Controller();
        $subject->renderTable('');
        @$this->assertTag(
            array(
                'tag' => 'script',
                'content' => 'var WDIR'
            ),
            $bjs
        );
    }

    /**
     * Tests that the JS is emitted.
     *
     * @return void
     *
     * @global string The (X)HTML fragment to insert at the bottom of the body.
     */
    public function testEmitsJs()
    {
        global $bjs;

        $subject = new Wdir_Controller();
        $subject->renderTable('');
        @$this->assertTag(
            array(
                'tag' => 'script',
                'attributes' => array(
                    'type' => 'text/javascript',
                    'src' => $this->path . '/wdir/wdir.js'
                )
            ),
            $bjs
        );
    }

    /**
     * Tests that the JS is emitted only once.
     *
     * @return void
     *
     * @global string The (X)HTML fragment to insert at the bottom of the body.
     */
    public function testEmitsJsOnlyOnce()
    {
        global $bjs;

        $subject = new Wdir_Controller();
        $subject->renderTable('');
        $bjs = '';
        $subject->renderTable('');
        $this->assertEmpty($bjs);
    }

    /**
     * Tests that the table is rendered.
     *
     * @return void
     */
    public function testRendersTable()
    {
        $subject = new Wdir_Controller();
        @$this->assertTag(
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
        @$this->assertTag(
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
        @$this->assertTag(
            array(
                'tag' => 'tbody',
                'children' => array(
                    'count' => 3,
                    'only' => array('tag' => 'tr')
                ),
                'parent' => array('tag' => 'table')
            ),
            $subject->renderTable('')
        );
    }

    /**
     * Tests that one body is row is rendered, when filtered with wildcard pattern.
     *
     * @return void
     */
    public function testRenders1BodyRowWhenFilteredWithWildcardPattern()
    {
        $subject = new Wdir_Controller();
        @$this->assertTag(
            array(
                'tag' => 'tbody',
                'children' => array(
                    'count' => 1,
                    'only' => array('tag' => 'tr')
                )
            ),
            $subject->renderTable('', '*.pdf')
        );
    }

    /**
     * Tests that one body is row is rendered, when filtered with regexp pattern.
     *
     * @return void
     */
    public function testRenders1BodyRowWhenFilteredWithRegexpPattern()
    {
        global $plugin_cf;

        $plugin_cf['wdir']['filter_regexp'] = 'true';
        $subject = new Wdir_Controller();
        @$this->assertTag(
            array(
                'tag' => 'tbody',
                'children' => array(
                    'count' => 1,
                    'only' => array('tag' => 'tr')
                )
            ),
            $subject->renderTable('', '/\.pdf$/')
        );
    }

    /**
     * Tests that a cell is rendered.
     *
     * @param string $name    A column name.
     * @param string $content A content.
     * @param string $value   A cell value.
     *
     * @return void
     *
     * @dataProvider cellData
     */
    public function testRendersCell($name, $content, $value)
    {
        $subject = new Wdir_Controller();
        @$this->assertTag(
            array(
                'tag' => 'td',
                'attributes' => array(
                    'class' => 'wdir_' . $name,
                    'data-wdir' => $value
                ),
                'content' => $content,
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
            array('name', 'one.txt', 'one.txt'),
            array('size', '0 KB', '0')
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
        @$this->assertTag(
            array(
                'tag' => 'td',
                'attributes' => array(
                    'class' => 'wdir_modified',
                    'data-wdir' => filemtime($this->path . '/one.txt')
                ),
                'content' => date(
                    'm/d/Y h:i a', filemtime($this->path . '/one.txt')
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
        @$this->assertTag(
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
        @$this->assertTag(
            array(
                'tag' => 'td',
                'attributes' => array('class' => 'wdir_name'),
                'child' => array(
                    'tag' => 'a',
                    'attributes' => array(
                        'href' => $this->path . '/one.txt',
                        'target' => '_blank'
                    )
                )
            ),
            $subject->renderTable('')
        );
    }
}

?>
