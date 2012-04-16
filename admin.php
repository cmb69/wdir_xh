<?php
echo 1;
if (!empty($wdir)) {
    $o .= print_plugin_admin('off');
    switch ($admin) {
	case '':
	    $o .= 'INFO';
	    break;
	default:
	    $o .= plugin_admin_common($action, $admin, $plugin);
    }
}

?>
