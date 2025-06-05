<?php

/**
 * Copyright (c) Christoph M. Becker
 *
 * This file is part of Wdir_XH.
 *
 * Wdir_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Wdir_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Wdir_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Wdir;

use ApprovalTests\Approvals;
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Plib\View;

class ControllerTest extends TestCase
{
    protected string $path;
    private View $view;

    protected function setUp(): void
    {
        global $plugin_cf, $plugin_tx;

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
        mkdir($this->path . '/downloads/', 0777);
        touch($this->path . '/one.txt', 1749127703);
        touch($this->path . '/two.pdf', 1749127703);
        touch($this->path . '/three', 1749127703);
        mkdir($this->path . '/wdir/images', 0777, true);
        touch($this->path . '/wdir/images/file-txt.png', 1749127703);
        $this->view = new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["wdir"]);
    }

    private function sut(): Controller
    {
        return new Controller($this->path . '/wdir/', $this->path . "/", $this->view);
    }

    public function testRendersTable(): void
    {
        $output = $this->sut()->renderTable('downloads');
        Approvals::verifyHtml($output);
    }

    public function testRendersColumnHeading(): void
    {
        $subject = $this->sut();
        $output = $subject->renderTable('');
        Approvals::verifyHtml($output);
    }

    public function testRenders1BodyRowWhenFilteredWithWildcardPattern(): void
    {
        $subject = $this->sut();
        $output = $subject->renderTable('', '*.pdf');
        Approvals::verifyHtml($output);
    }

    public function testRenders1BodyRowWhenFilteredWithRegexpPattern(): void
    {
        global $plugin_cf;

        $plugin_cf['wdir']['filter_regexp'] = 'true';
        $subject = $this->sut();
        $output = $subject->renderTable('', '/\.pdf$/');
        Approvals::verifyHtml($output);
    }
}
