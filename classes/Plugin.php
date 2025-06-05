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

class Plugin
{
    public const VERSION = "1.1";

    public static function controller(): Controller
    {
        global $pth;
        return new Controller(
            $pth["folder"]["plugins"] . "wdir/",
            $pth["folder"]["userfiles"],
            self::view()
        );
    }

    public static function infoCommand(): InfoCommand
    {
        return new InfoCommand();
    }

    private static function view(): View
    {
        global $pth, $plugin_tx;
        return new View($pth["folder"]["plugins"] . "wdir/views/", $plugin_tx["wdir"]);
    }
}
