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
