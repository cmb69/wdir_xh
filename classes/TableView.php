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

namespace Wdir;

/**
 * The table views.
 *
 * @category CMSimple_XH
 * @package  Wdir
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Wdir_XH
 */
class TableView
{
    protected Folder $folder;

    public function __construct(Folder $folder)
    {
        $this->folder = $folder;
    }

    public function render(): string
    {
        $html = '<table class="wdir_table">'
            . $this->renderHead() . $this->renderBody()
            . '</table>';
        return $html;
    }

    protected function renderHead(): string
    {
        global $plugin_tx;

        $ptx = $plugin_tx['wdir'];
        return '<thead><tr>' . "\n"
            . '<td>' . $ptx['label_name'] . '</td>' . "\n"
            . '<td>' . $ptx['label_size'] . '</td>' . "\n"
            . '<td>' . $ptx['label_modified'] . '</td>' . "\n"
            . '</tr></thead>' . "\n";
    }

    protected function renderBody(): string
    {
        $html = '<tbody>';
        foreach ($this->folder->getFiles() as $file) {
            $html .= $this->renderBodyRow($file);
        }
        $html .= '</tbody>';
        return $html;
    }

    protected function renderBodyRow(File $file): string
    {
        global $plugin_tx;

        $time = date($plugin_tx['wdir']['format_date'], $file->getModificationTime());
        return '<tr>' . "\n"
            . '<td class="wdir_name" data-wdir="' . $file->getName() . '">'
            . $this->renderFileIcon($file)
            . '<a href="' . $file->getPath() . '" target="_blank">'
            . $file->getName() . '</a>' . '</td>' . "\n"
            . '<td class="wdir_size" data-wdir="' . $file->getSize() . '">'
            . $this->renderFileSize($file) . '</td>' . "\n"
            . '<td class="wdir_modified" data-wdir="'
            . $file->getModificationTime() . '">' . $time . '</td>' . "\n"
            . '</tr>' . "\n";
    }

    protected function renderFileSize(File $file): string
    {
        return ceil($file->getSize() / 1024) . ' KB';
    }

    /** @todo alt attribute! */
    protected function renderFileIcon(File $file): string
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
