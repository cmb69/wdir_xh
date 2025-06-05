<?php

/**
 * The main program.
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
