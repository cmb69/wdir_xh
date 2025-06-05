<?php

namespace Wdir;

use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;

class InfoCommandTest extends TestCase
{
    protected function setUp(): void
    {
        global $pth, $plugin_tx;
        $pth = ["folder" => ["plugins" => "./plugins/"]];
        $plugin_tx = XH_includeVar("./languages/en.php", "plugin_tx");
    }

    private function sut(): InfoCommand
    {
        return new InfoCommand();
    }

    public function testRendersInfo(): void
    {
        $output = $this->sut()();
        Approvals::verifyHtml($output);
    }
}
