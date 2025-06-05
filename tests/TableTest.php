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

class TableTest extends TestCase
{
    protected string $path;

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

    public function testJSConfigurationIsWrittenToBJS(): void
    {
        global $bjs;

        $subject = new Controller();
        $subject->renderTable('');
        Approvals::verifyHtml($bjs);
    }

    public function testEmitsJsOnlyOnce(): void
    {
        global $bjs;

        $subject = new Controller();
        $subject->renderTable('');
        $bjs = '';
        $subject->renderTable('');
        $this->assertEmpty($bjs);
    }

    public function testRendersTable(): void
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

    public function testRendersColumnHeading(): void
    {
        $subject = new Controller();
        $output = $subject->renderTable('');
        Approvals::verifyHtml($output);
    }

    public function testRenders1BodyRowWhenFilteredWithWildcardPattern(): void
    {
        $subject = new Controller();
        $output = $subject->renderTable('', '*.pdf');
        Approvals::verifyHtml($output);
    }

    public function testRenders1BodyRowWhenFilteredWithRegexpPattern(): void
    {
        global $plugin_cf;

        $plugin_cf['wdir']['filter_regexp'] = 'true';
        $subject = new Controller();
        $output = $subject->renderTable('', '/\.pdf$/');
        Approvals::verifyHtml($output);
    }
}
