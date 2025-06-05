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

use Wdir\Controller;

/*
 * Prevent direct access and usage from unsupported CMSimple_XH versions.
 */
if (
    !defined('CMSIMPLE_XH_VERSION')
    || strpos(CMSIMPLE_XH_VERSION, 'CMSimple_XH') !== 0
    || version_compare(CMSIMPLE_XH_VERSION, 'CMSimple_XH 1.6', 'lt') // @phpstan-ignore-line
) {
    header('HTTP/1.1 403 Forbidden');
    header('Content-Type: text/plain; charset=UTF-8');
    die(
        <<<EOT
Wdir_XH detected an unsupported CMSimple_XH version.
Deinstall Wdir_XH or upgrade to a supported CMSimple_XH version!
EOT
    );
}

/**
 * The plugin version.
 */
define('WDIR_VERSION', '1.1');

function wdir(string $path, string $filter = ""): string
{
    global $_Wdir_controller;

    return $_Wdir_controller->renderTable(
        html_entity_decode($path, ENT_QUOTES, 'UTF-8'),
        html_entity_decode($filter, ENT_QUOTES, 'UTF-8')
    );
}

/**
 * The plugin controller.
 */
$_Wdir_controller = new Controller();
$_Wdir_controller->dispatch();
