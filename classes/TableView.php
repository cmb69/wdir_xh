<?php

/**
 * The table views.
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
     * The folder.
     *
     * @var Wdir_Folder
     */
    protected $folder;

    /**
     * Initializes a new instance.
     *
     * @param Wdir_Folder $folder A folder.
     *
     * @return void
     */
    public function __construct(Wdir_Folder $folder)
    {
        $this->folder = $folder;
    }

    /**
     * Renders the view.
     *
     * @return string (X)HTML.
     */
    public function render()
    {
        $html = '<table class="wdir_table">'
            . $this->renderHead() . $this->renderBody()
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
    protected function renderHead()
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
    protected function renderBody()
    {
        $html = '<tbody>';
        foreach ($this->folder->getFiles() as $file) {
            $html .= $this->renderBodyRow($file);
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
    protected function renderBodyRow($file)
    {
        global $plugin_tx;

        $time = date(
            $plugin_tx['wdir']['format_date'], $file->getModificationTime()
        );
        return '<tr>'
            . '<td class="wdir_name" data-wdir="' . $file->getName() . '">'
            . $this->renderFileIcon($file)
            . '<a href="' . $file->getPath() . '" target="_blank">'
            . $file->getName() . '</a>' . '</td>'
            . '<td class="wdir_size" data-wdir="' . $file->getSize() . '">'
            . $this->renderFileSize($file) . '</td>'
            . '<td class="wdir_modified" data-wdir="'
            . $file->getModificationTime() . '">' . $time . '</td>'
            . '</tr>';
    }

    /**
     * Returns the size of a file in KB (rounded up).
     *
     * @param Wdir_File $file A file.
     *
     * @return string (X)HTML.
     */
    protected function renderFileSize($file)
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
    protected function renderFileIcon($file)
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
