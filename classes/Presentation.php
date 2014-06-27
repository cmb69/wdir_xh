<?php

/**
 * The presentation layer.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Wdir
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2014 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   SVN: $Id$
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
     * Dispatches according to the request.
     *
     * @return void
     *
     * @global string Whether the plugin administration is requested.
     */
    public function dispatch()
    {
        global $wdir;

        if (XH_ADM && isset($wdir) && $wdir == 'true') {
            $this->_handleAdministration();
        }
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
    private function _handleAdministration()
    {
        global $admin, $action, $o;

        $o .= print_plugin_admin('off');
        switch ($admin) {
        case '':
            $o .= $this->_renderInfo();
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
    private function _renderInfo()
    {
        return '<h1>Wdir</h1>'
            . $this->_renderIcon()
            . '<p>Version: ' . WDIR_VERSION . '</p>'
            . $this->_renderCopyright() . $this->_renderLicense();
    }

    /**
     * Renders the plugin icon.
     *
     * @return string (X)HTML.
     *
     * @global array The paths of system files and folders.
     * @global array The localization of the plugins.
     */
    private function _renderIcon()
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
    private function _renderCopyright()
    {
        return <<<EOT
<p>Copyright &copy; 2012-2014
    <a href="http://3-magi.net/" target="_blank">Christoph M. Becker</a>
</p>
EOT;
    }

    /**
     * Renders the license info.
     *
     * @return string (X)HTML.
     */
    private function _renderLicense()
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
     * @param string $path A folder path.
     *
     * @return string (X)HTML.
     */
    public function renderTable($path)
    {
        $view = new Wdir_TableView($path);
        return $view->render();
    }
}

/**
 * The table views.
 *
 * @category CMSimple_XH
 * @package  Wdir
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Wdir_XH
 */
class Wdir_TableView
{
    /**
     * The path of the folder.
     *
     * @var string
     */
    private $_path;

    /**
     * Initializes a new instance.
     *
     * @param string $path A folder path.
     *
     * @return void
     */
    public function __construct($path)
    {
        $this->_path = (string) $path;
    }

    /**
     * Renders the view.
     *
     * @return string (X)HTML.
     */
    public function render()
    {
        $html = '<table class="wdir_table">'
            . $this->_renderHead() . $this->_renderBody()
            . '</table>';
        return $html;
    }

    /**
     * Renders the table head.
     *
     * @return string (X)HTML.
     *
     * @global array The localization of the plugins.
     */
    private function _renderHead()
    {
        global $plugin_tx;

        $ptx = $plugin_tx['wdir'];
        return '<thead><tr>'
            . '<td>' . $ptx['label_name'] . '</td>'
            . '<td>' . $ptx['label_size'] . '</td>'
            . '<td>' . $ptx['label_modified'] . '</td>'
            . '</tr></thead>';
    }

    /**
     * Renders the table body.
     *
     * @return string (X)HTML.
     */
    private function _renderBody()
    {
        $html = '<tbody>';
        $folder = new Wdir_Folder($this->_path);
        foreach ($folder->getFiles() as $file) {
            $html .= $this->_renderBodyRow($file);
        }
        $html .= '</tbody>';
        return $html;
    }

    /**
     * Renders a table body row.
     *
     * @param Wdir_File $file A file.
     *
     * @return string (X)HTML.
     *
     * @global array The localization of the plugins.
     */
    private function _renderBodyRow($file)
    {
        global $plugin_tx;

        $time = date(
            $plugin_tx['wdir']['format_date'], $file->getModificationTime()
        );
        return '<tr>'
            . '<td class="wdir_name">' . $this->_renderFileIcon($file)
            . '<a href="' . $file->getPath() . '" target="_blank">'
            . $file->getName() . '</a>' . '</td>'
            . '<td class="wdir_size">' . $this->_renderFileSize($file) . '</td>'
            . '<td class="wdir_modified">' . $time . '</td>'
            . '</tr>';
    }

    /**
     * Returns the size of a file in KB (rounded up).
     *
     * @param Wdir_File $file A file.
     *
     * @return string (X)HTML.
     */
    private function _renderFileSize($file)
    {
        return ceil($file->getSize() / 1024) . ' KB';
    }

    /**
     * Renders a file icon.
     *
     * @param Wdir_File $file A file.
     *
     * @return string (X)HTML.
     *
     * @global array The paths of system files and folders.
     * @global array The localization of the plugins.
     *
     * @todo alt attribute!
     */
    private function _renderFileIcon($file)
    {
        global $pth, $plugin_tx;

        $ext = $file->getExtension();
        $imageFolder = $pth['folder']['plugins'] . 'wdir/images/';
        $src = $imageFolder . 'file-' . $ext . '.png';
        if (file_exists($src)) {
            $alt = sprintf($plugin_tx['wdir']['format_type'], strtoupper($ext));
        } else {
            $src = $imageFolder . 'file.png';
            $alt = $plugin_tx['wdir']['label_file'];
        }
        return tag(
            'img src="' . $src . '" alt="' . $alt . '" title="' . $alt . '"'
        );
    }
}

?>
