<?php

/**
 * The main program.
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

/*
 * Prevent direct access and usage from unsupported CMSimple_XH versions.
 */
if (!defined('CMSIMPLE_XH_VERSION')
    || strpos(CMSIMPLE_XH_VERSION, 'CMSimple_XH') !== 0
    || version_compare(CMSIMPLE_XH_VERSION, 'CMSimple_XH 1.6', 'lt')
) {
    header('HTTP/1.1 403 Forbidden');
    header('Content-Type: text/plain; charset=UTF-8');
    die(
        'Unsupported CMSimple_XH version detected. Deinstall Wdir_XH'
        . ' or upgrade to a supported CMSimple_XH version!'
    );
}

/**
 * The domain layer.
 */
require_once $pth['folder']['plugin_classes'] . 'Domain.php';

/**
 * The presentation layer.
 */
require_once $pth['folder']['plugin_classes'] . 'Presentation.php';

/**
 * The plugin version.
 */
define('WDIR_VERSION', '@WDIR_VERSION@');

/**
 * Returns the wdir table view.
 *
 * @param string $path   A folder path.
 * @param string $filter A filter expression.
 *
 * @return string (X)HTML.
 *
 * @global Wdir_Controller The plugin controller.
 */
function wdir($path, $filter = false)
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
$_Wdir_controller = new Wdir_Controller();
$_Wdir_controller->dispatch();

?>
