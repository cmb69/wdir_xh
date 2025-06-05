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
