<?php

/**
 * Testing the info view.
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

use PHPUnit\Framework\TestCase;

class InfoViewTest extends TestCase
{
    protected Controller $subject;

    protected function setUp(): void
    {
        global $wdir, $o, $pth, $plugin_tx;

        $this->markTestSkipped("requires function mocking");
        $this->defineConstant('XH_ADM', true);
        $this->defineConstant('WDIR_VERSION', '1.0');
        $wdir = 'true';
        $o = '';
        $pth = array(
            'folder' => array('plugins' => './plugins/')
        );
        $plugin_tx = array(
            'wdir' => array('alt_icon' => 'Facebook')
        );
        $this->subject = new Controller();
        new PHPUnit_Extensions_MockFunction('XH_registerStandardPluginMenuItems', $this->subject);
        new PHPUnit_Extensions_MockFunction('print_plugin_admin', $this->subject);
        $this->subject->dispatch();
    }

    public function testRendersHeading(): void
    {
        global $o;

        @$this->assertTag(
            array(
                'tag' => 'h1',
                'content' => 'Wdir'
            ),
            $o
        );
    }

    public function testRendersIcon(): void
    {
        global $o;

        @$this->assertTag(
            array(
                'tag' => 'img',
                'attributes' => array(
                    'src' => './plugins/wdir/wdir.png',
                    'class' => 'wdir_icon',
                    'alt' => 'Facebook'
                )
            ),
            $o
        );
    }

    public function testRendersVersion(): void
    {
        global $o;

        @$this->assertTag(
            array(
                'tag' => 'p',
                'content' => 'Version: ' . WDIR_VERSION
            ),
            $o
        );
    }

    public function testRendersCopyright(): void
    {
        global $o;

        @$this->assertTag(
            array(
                'tag' => 'p',
                'content' => "Copyright \xC2\xA9 2012-2015",
                'child' => array(
                    'tag' => 'a',
                    'attributes' => array(
                        'href' => 'http://3-magi.net/',
                        'target' => '_blank'
                    ),
                    'content' => 'Christoph M. Becker'
                )
            ),
            $o
        );
    }

    public function testRendersLicense(): void
    {
        global $o;

        @$this->assertTag(
            array(
                'tag' => 'p',
                'attributes' => array('class' => 'wdir_license'),
                'content' => 'This program is free software:'
            ),
            $o
        );
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
