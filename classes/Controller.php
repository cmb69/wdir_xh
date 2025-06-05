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

class Controller
{
    private bool $isJsEmitted = false;

    public function renderTable(string $path, string $filter = ""): string
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
        return $this->render(new Folder($path, $filter));
    }

    private function emitJs(): void
    {
        global $pth, $plugin_cf, $bjs;

        $config = array(
            'caseInsensitive' => $plugin_cf['wdir']['sort_column'] == 'name/i'
        );
        $bjs .= '<script type="text/javascript">/* <![CDATA[ */'
            . 'var WDIR = ' . json_encode($config) . ';'
            . '/* ]]> */</script>' . "\n"
            . '<script type="text/javascript" src="' . $pth['folder']['plugins']
            . 'wdir/wdir.js"></script>' . "\n";
    }

    private function render(Folder $folder): string
    {
        $html = '<table class="wdir_table">'
            . $this->renderHead() . $this->renderBody($folder)
            . '</table>';
        return $html;
    }

    private function renderHead(): string
    {
        global $plugin_tx;

        $ptx = $plugin_tx['wdir'];
        return '<thead><tr>' . "\n"
            . '<td>' . $ptx['label_name'] . '</td>' . "\n"
            . '<td>' . $ptx['label_size'] . '</td>' . "\n"
            . '<td>' . $ptx['label_modified'] . '</td>' . "\n"
            . '</tr></thead>' . "\n";
    }

    private function renderBody(Folder $folder): string
    {
        $html = '<tbody>';
        foreach ($folder->getFiles() as $file) {
            $html .= $this->renderBodyRow($file);
        }
        $html .= '</tbody>';
        return $html;
    }

    private function renderBodyRow(File $file): string
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

    private function renderFileSize(File $file): string
    {
        return ceil($file->getSize() / 1024) . ' KB';
    }

    /** @todo alt attribute! */
    private function renderFileIcon(File $file): string
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
        return '<img src="' . $src . '" alt="' . $alt . '" title="' . $alt . '">';
    }
}
