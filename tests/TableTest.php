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

namespace Wdir;

use ApprovalTests\Approvals;
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

/**
 * Testing the table view.
 *
 * @category Testing
 * @package  Wdir
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Wdir_XH
 */
class TableTest extends TestCase
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
    protected function setUp(): void
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
        touch($this->path . '/one.txt', 1749127703);
        touch($this->path . '/two.pdf', 1749127703);
        touch($this->path . '/three', 1749127703);
        mkdir($this->path . '/wdir/images', 0777, true);
        touch($this->path . '/wdir/images/file-txt.png', 1749127703);
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

        $subject = new Controller();
        $subject->renderTable('');
        Approvals::verifyHtml($bjs);
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

        $subject = new Controller();
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
        $subject = new Controller();
        $output = $subject->renderTable('downloads');
        $this->assertSame(
            "<table class=\"wdir_table\"><thead><tr>\n"
            . "<td>Name</td>\n<td>Size</td>\n<td>Modified</td>\n</tr></thead>\n"
            . '<tbody></tbody></table>',
            $output
        );
    }

    /**
     * Tests that a column heading is rendered.
     *
     * @return void
     */
    public function testRendersColumnHeading()
    {
        $subject = new Controller();
        $output = $subject->renderTable('');
        Approvals::verifyHtml($output);
    }

    /**
     * Tests that one body is row is rendered, when filtered with wildcard pattern.
     *
     * @return void
     */
    public function testRenders1BodyRowWhenFilteredWithWildcardPattern()
    {
        $subject = new Controller();
        $output = $subject->renderTable('', '*.pdf');
        Approvals::verifyHtml($output);
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
        $subject = new Controller();
        $output = $subject->renderTable('', '/\.pdf$/');
        Approvals::verifyHtml($output);
    }
}
