<?php

/**
 * The autoloader.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Testing
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2015 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Wdir_XH
 */

spl_autoload_register(
    function ($class) {
        global $pth;

        $parts = explode('_', $class, 2);
        if ($parts[0] == 'Wdir') {
            include_once './classes/' . $parts[1] . '.php';
        }
    }
);

?>
