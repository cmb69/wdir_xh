<?php

namespace Wdir;

use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;
use Plib\View;

class InfoCommandTest extends TestCase
{
    private View $view;

    protected function setUp(): void
    {
        $this->view = new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["wdir"]);
    }

    private function sut(): InfoCommand
    {
        return new InfoCommand("./plugins/wdir/", $this->view);
    }

    public function testRendersInfo(): void
    {
        $output = $this->sut()();
        Approvals::verifyHtml($output);
    }
}
