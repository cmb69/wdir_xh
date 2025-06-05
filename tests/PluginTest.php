<?php

namespace Wdir;

use PHPUnit\Framework\TestCase;

class PluginTest extends TestCase
{
    public function testMakesController(): void
    {
        $this->assertInstanceOf(Controller::class, Plugin::controller());
    }

    public function testMakesInfoCommand(): void
    {
        $this->assertInstanceOf(InfoCommand::class, Plugin::infoCommand());
    }
}
