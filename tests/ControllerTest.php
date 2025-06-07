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
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Plib\FakeRequest;
use Plib\View;

class ControllerTest extends TestCase
{
    private string $path;
    private array $conf;
    private View $view;

    protected function setUp(): void
    {
        vfsStream::setup("test", null, [
            "downloads" => [],
            "one.txt" => "***",
            "two.pdf" => "**",
            "three" => "*",
        ]);
        $this->path = vfsStream::url("test");
        touch($this->path . "/one.txt", strtotime("2025-06-06T12:48:23+00:00"));
        touch($this->path . "/two.pdf", strtotime("2025-06-05T12:48:23+00:00"));
        touch($this->path . "/three", strtotime("2025-06-07T12:48:23+00:00"));
        $this->conf = XH_includeVar("./config/config.php", "plugin_cf")["wdir"];
        $this->view = new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["wdir"]);
    }

    private function sut(): Controller
    {
        return new Controller("./", $this->path, $this->conf, $this->view);
    }

    public function testRendersTable(): void
    {
        $request = new FakeRequest();
        $output = $this->sut()->renderTable($request, "");
        Approvals::verifyHtml($output);
    }

    public function testRendersTableFilteredWithWildcardPattern(): void
    {
        $request = new FakeRequest();
        $output = $this->sut()->renderTable($request, "", "*.pdf");
        Approvals::verifyHtml($output);
    }

    public function testRendersTableFilteredWithRegexpPattern(): void
    {
        $this->conf["filter_regexp"] = "true";
        $request = new FakeRequest();
        $output = $this->sut()->renderTable($request, "", '/\.pdf$/');
        Approvals::verifyHtml($output);
    }

    public function testRendersTableSortedBySize(): void
    {
        $this->conf["sort_column"] = "size";
        $request = new FakeRequest();
        $output = $this->sut()->renderTable($request, "");
        Approvals::verifyHtml($output);
    }

    public function testRendersTableSortedByDate(): void
    {
        $this->conf["sort_column"] = "date";
        $request = new FakeRequest();
        $output = $this->sut()->renderTable($request, "");
        Approvals::verifyHtml($output);
    }

    public function testRendersUnsortedTable(): void
    {
        $this->conf["sort_column"] = "";
        $request = new FakeRequest();
        $output = $this->sut()->renderTable($request, "");
        Approvals::verifyHtml($output);
    }
}
