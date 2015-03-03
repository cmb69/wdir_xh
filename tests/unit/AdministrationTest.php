<?php

/**
 * Testing the general plugin administration.
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

require_once './vendor/autoload.php';
require_once '../../cmsimple/adminfuncs.php';

/**
 * Testing the general plugin administration.
 *
 * @category Testing
 * @package  Wdir
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Wdir_XH
 */
class AdministrationTest extends PHPUnit_Framework_TestCase
{
    /**
     * The test subject.
     *
     * @var Wdir_Controller
     */
    protected $subject;

    /**
     * The XH_registerStandardPluginMenuItems() mock.
     *
     * @var object
     */
    protected $registerStandardPluginMenuItemsMock;

    /**
     * The print_plugin_admin() mock.
     *
     * @var object
     */
    protected $printPluginAdminMock;

    /**
     * The plugin_admin_common() mock.
     *
     * @var object
     */
    protected $pluginAdminCommonMock;

    /**
     * Sets up the test fixture.
     *
     * @return void
     */
    public function setUp()
    {
        $this->defineConstant('XH_ADM', true);
        $this->subject = new Wdir_Controller();
        $this->registerStandardPluginMenuItemsMock
            = new PHPUnit_Extensions_MockFunction(
                'XH_registerStandardPluginMenuItems', $this->subject
            );
        $this->printPluginAdminMock = new PHPUnit_Extensions_MockFunction(
            'print_plugin_admin', $this->subject
        );
        $this->pluginAdminCommonMock = new PHPUnit_Extensions_MockFunction(
            'plugin_admin_common', $this->subject
        );
    }

    /**
     * Tests that the standard plugin menu items are registered.
     *
     * @return void
     */
    public function testStandardPluginMenuItemsAreRegistered()
    {
        $this->registerStandardPluginMenuItemsMock->expects($this->once())
            ->with(false);
        $this->subject->dispatch();
    }

    /**
     * Tests the stylesheet administration.
     *
     * @return void
     *
     * @global string Whether the plugin administration is requested.
     * @global string The value of the <var>admin</var> GP parameter.
     * @global string The value of the <var>action</var> GP parameter.
     */
    public function testStylesheet()
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

    /**
     * Defines resp. redefines a constant.
     *
     * @param string $name  A name.
     * @param string $value A value.
     *
     * @return void
     */
    protected function defineConstant($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        } else {
            runkit_constant_redefine($name, $value);
        }
    }
}

?>
