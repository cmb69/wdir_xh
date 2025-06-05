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
    protected bool $isJsEmitted = false;

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
        $view = new TableView(new Folder($path, $filter));
        return $view->render();
    }

    protected function emitJs(): void
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
}
