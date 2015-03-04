<?php

/**
 * The presentation layer.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Wdir
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2015 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Wdir_XH
 */

/**
 * The controllers.
 *
 * @category CMSimple_XH
 * @package  Wdir
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Wdir_XH
 */
class Wdir_Controller
{
    /**
     * Whether the JS has already been emitted.
     *
     * @var bool
     */
    protected $isJsEmitted = false;

    /**
     * Dispatches according to the request.
     *
     * @return void
     */
    public function dispatch()
    {
        if (XH_ADM) {
            if (function_exists('XH_registerStandardPluginMenuItems')) {
                XH_registerStandardPluginMenuItems(false);
            }
            if ($this->isAdministrationRequested()) {
                $this->handleAdministration();
            }
        }
    }

    /**
     * Returns whether the plugin administration is requested.
     *
     * @return bool
     *
     * @global string Whether the plugin administration is requested.
     */
    protected function isAdministrationRequested()
    {
        global $wdir;

        return function_exists('XH_wantsPluginAdministration')
            && XH_wantsPluginAdministration('wdir')
            || isset($wdir) && $wdir == 'true';
    }

    /**
     * Handles the plugin administration.
     *
     * @return void
     *
     * @global string The value of the <var>admin</var> GP parameter.
     * @global string The value of the <var>action</var> GP parameter.
     * @global string The (X)HTML fragment to insert into the contents area.
     */
    protected function handleAdministration()
    {
        global $admin, $action, $o;

        $o .= print_plugin_admin('off');
        switch ($admin) {
        case '':
            $o .= $this->renderInfo();
            break;
        default:
            $o .= plugin_admin_common($action, $admin, 'wdir');
        }
    }

    /**
     * Renders the plugin info.
     *
     * @return string (X)HTML.
     */
    protected function renderInfo()
    {
        return '<h1>Wdir</h1>'
            . $this->renderIcon()
            . '<p>Version: ' . WDIR_VERSION . '</p>'
            . $this->renderCopyright() . $this->renderLicense();
    }

    /**
     * Renders the plugin icon.
     *
     * @return string (X)HTML.
     *
     * @global array The paths of system files and folders.
     * @global array The localization of the plugins.
     */
    protected function renderIcon()
    {
        global $pth, $plugin_tx;

        return tag(
            'img src="' . $pth['folder']['plugins']
            . 'wdir/wdir.png" class="wdir_icon"'
            . ' alt="' . $plugin_tx['wdir']['alt_icon'] . '"'
        );
    }

    /**
     * Renders the copyright info.
     *
     * @return string (X)HTML.
     */
    protected function renderCopyright()
    {
        return <<<EOT
<p>Copyright &copy; 2012-2015
    <a href="http://3-magi.net/" target="_blank">Christoph M. Becker</a>
</p>
EOT;
    }

    /**
     * Renders the license info.
     *
     * @return string (X)HTML.
     */
    protected function renderLicense()
    {
        return <<<EOT
<p class="wdir_license">This program is free software: you can
redistribute it and/or modify it under the terms of the GNU General Public
License as published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.</p>
<p class="wdir_license">This program is distributed in the hope that it
will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHAN&shy;TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General
Public License for more details.</p>
<p class="wdir_license">You should have received a copy of the GNU
General Public License along with this program. If not, see <a
href="http://www.gnu.org/licenses/" target="_blank">http://www.gnu.org/licenses/</a>.
</p>
EOT;
    }

    /**
     * Renders the table.
     *
     * @param string $path   A folder path.
     * @param string $filter A filter expression.
     *
     * @return string (X)HTML.
     *
     * @global array The paths of system files and folders.
     */
    public function renderTable($path, $filter = false)
    {
        global $pth;

        if (!$this->isJsEmitted) {
            $this->isJsEmitted = true;
            $this->emitJs();
        }
        $path = $pth['folder']['userfiles'] . (string) $path;
        if ($path[strlen($path) - 1] != '/') {
            $path .= '/';
        }
        $view = new Wdir_TableView(new Wdir_Folder($path, $filter));
        return $view->render();
    }

    /**
     * Emits the JS to the bottom of the body element.
     *
     * @return void
     *
     * @global array  The paths of system files and folders.
     * @global array  The configuration of the plugins.
     * @global string The (X)HTML fragment to insert at the bottom of the body.
     */
    protected function emitJs()
    {
        global $pth, $plugin_cf, $bjs;

        $config = array(
            'caseInsensitive' => $plugin_cf['wdir']['sort_column'] == 'name/i'
        );
        $bjs .= '<script type="text/javascript">/* <![CDATA[ */'
            . 'var WDIR = ' . json_encode($config) . ';'
            . '/* ]]> */</script>'
            . '<script type="text/javascript" src="' . $pth['folder']['plugins']
            . 'wdir/wdir.js"></script>';
    }
}

?>
