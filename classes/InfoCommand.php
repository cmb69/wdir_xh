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

use Plib\View;

class InfoCommand
{
    private string $pluginFolder;
    private View $view;

    public function __construct(string $pluginFolder, View $view)
    {
        $this->pluginFolder = $pluginFolder;
        $this->view = $view;
    }

    public function __invoke(): string
    {
        return '<h1>Wdir</h1>' . "\n"
            . $this->renderIcon() . "\n"
            . '<p>Version: ' . Plugin::VERSION . '</p>' . "\n"
            . $this->renderCopyright() . "\n" . $this->renderLicense() . "\n";
    }

    private function renderIcon(): string
    {
        return '<img src="' . $this->pluginFolder
            . 'wdir.png" class="wdir_icon"'
            . ' alt="' . $this->view->text("alt_icon") . '">';
    }

    private function renderCopyright(): string
    {
        return <<<EOT
<p>Copyright &copy; 2012-2015
    <a href="http://3-magi.net/" target="_blank">Christoph M. Becker</a>
</p>
EOT;
    }

    private function renderLicense(): string
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
}
