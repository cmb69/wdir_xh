<?php

/**
 * Testing the info view.
 *
 * PHP version 5
 *
 * @category  Testing
 * @package   Wdir
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2014 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   SVN: $Id$
 * @link      http://3-magi.net/?CMSimple_XH/Wdir_XH
 */

require_once './vendor/autoload.php';
require_once '../../cmsimple/functions.php';
require_once '../../cmsimple/adminfuncs.php';
require_once './classes/Presentation.php';

/**
 * Testing the info view.
 *
 * @category Testing
 * @package  Wdir
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Wdir_XH
 */
class InfoViewTest extends PHPUnit_Framework_TestCase
{
    /**
     * The subject under test.
     *
     * @var Wdir_Controller
     */
    private $_subject;

    /**
     * Sets up the test fixture.
     *
     * @return void
     *
     * @global string Whether the plugin administration is requested.
     * @global string The (X)HTML of the contents area.
     * @global array  The paths of system files and folders.
     * @global array  The localization of the plugins.
     */
    public function setUp()
    {
        global $wdir, $o, $pth, $plugin_tx;

        $this->_defineConstant('XH_ADM', true);
        $this->_defineConstant('WDIR_VERSION', '1.0');
        $wdir = 'true';
        $o = '';
        $pth = array(
            'folder' => array('plugins' => './plugins/')
        );
        $plugin_tx = array(
            'wdir' => array('alt_icon' => 'Facebook')
        );
        $this->_subject = new Wdir_Controller();
        $printPluginAdmin = new PHPUnit_Extensions_MockFunction(
            'print_plugin_admin', $this->_subject
        );
        $this->_subject->dispatch();
    }

    /**
     * Tests that the heading is rendered.
     *
     * @return void
     *
     * @global string The (X)HTML of the contents area.
     */
    public function testRendersHeading()
    {
        global $o;

        $this->assertTag(
            array(
                'tag' => 'h1',
                'content' => 'Wdir'
            ),
            $o
        );
    }

    /**
     * Tests that the plugin icon is rendered.
     *
     * @return void
     *
     * @global string The (X)HTML of the contents area.
     */
    public function testRendersIcon()
    {
        global $o;

        $this->assertTag(
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

    /**
     * Tests that the version info is rendered.
     *
     * @return void
     *
     * @global string The (X)HTML of the contents area.
     */
    public function testRendersVersion()
    {
        global $o;

        $this->assertTag(
            array(
                'tag' => 'p',
                'content' => 'Version: ' . WDIR_VERSION
            ),
            $o
        );
    }

    /**
     * Tests that the copyright info is rendered.
     *
     * @return void
     *
     * @global string The (X)HTML of the contents area.
     */
    public function testRendersCopyright()
    {
        global $o;

        $this->assertTag(
            array(
                'tag' => 'p',
                'content' => "Copyright \xC2\xA9 2012-2014",
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

    /**
     * Tests that the license info is rendered.
     *
     * @return void
     *
     * @global string The (X)HTML of the contents area.
     */
    public function testRendersLicense()
    {
        global $o;

        $this->assertTag(
            array(
                'tag' => 'p',
                'attributes' => array('class' => 'wdir_license'),
                'content' => 'This program is free software:'
            ),
            $o
        );
    }

    /**
     * (Re)defines a constant.
     *
     * @param string $name  A name.
     * @param string $value A value.
     *
     * @return void
     */
    private function _defineConstant($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        } else {
            runkit_constant_redefine($name, $value);
        }
    }
}

?>
