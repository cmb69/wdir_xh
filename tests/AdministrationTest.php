<?php

namespace Wdir;

use PHPUnit\Framework\TestCase;

class AdministrationTest extends TestCase
{
    protected Controller $subject;

    /** @var object */
    protected $registerStandardPluginMenuItemsMock;

    /** @var object */
    protected $printPluginAdminMock;

    /** @var object */
    protected $pluginAdminCommonMock;

    protected function setUp(): void
    {
        $this->markTestSkipped("requires function mocks");
        $this->defineConstant('XH_ADM', true);
        $this->subject = new Controller();
        $this->registerStandardPluginMenuItemsMock
            = new PHPUnit_Extensions_MockFunction('XH_registerStandardPluginMenuItems', $this->subject);
        $this->printPluginAdminMock = new PHPUnit_Extensions_MockFunction('print_plugin_admin', $this->subject);
        $this->pluginAdminCommonMock = new PHPUnit_Extensions_MockFunction('plugin_admin_common', $this->subject);
    }

    public function testStandardPluginMenuItemsAreRegistered(): void
    {
        $this->registerStandardPluginMenuItemsMock->expects($this->once())
            ->with(false);
        $this->subject->dispatch();
    }

    public function testStylesheet(): void
    {
        global $wdir, $admin, $action;

        $wdir = 'true';
        $admin = 'plugin_stylesheet';
        $action = 'plugin_text';
        $this->printPluginAdminMock->expects($this->once())->with('off');
        $this->pluginAdminCommonMock->expects($this->once())
            ->with($action, $admin, 'wdir');
        $this->subject->dispatch();
    }

    protected function defineConstant(string $name, string $value): void
    {
        if (!defined($name)) {
            define($name, $value);
        } else {
            runkit_constant_redefine($name, $value);
        }
    }
}
