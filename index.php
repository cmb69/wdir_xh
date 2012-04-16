<?php


/**
 * Returns $tx in its correct numerus according to $count.
 *
 * @param string $plugin  The name of the plugin.
 * @param string $tx  The base name of the language string.
 * @param int $count  The item's count.
 * @return string
 */
function cmb_numerus($plugin, $tx, $count) {
    global $plugin_tx;

    $ptx = $plugin_tx[$plugin];
    $limit = isset($ptx['paucal_limit']) ? $ptx['paucal_limit'] : 4;
    $singular = isset($ptx[$tx.'_singular']) ? $ptx[$tx.'_singular'] : $ptx[$tx];
    $paucal = isset($ptx[$tx.'_paucal']) ? $ptx[$tx.'_paucal']
	    : ($ptx[$tx.'_plural'] ? $ptx[$tx.'_plural'] : $ptx[$tx]);
    $plural = isset($ptx[$tx.'_plural']) ? $ptx[$tx.'_plural'] : $ptx[$tx];
    if ($count > $limit || $count == 0) {
	return $plural;
    } elseif ($count > 1) {
	return $paucal;
    } else {
	return $singular;
    }
}


/**
 * Returns all files in $folder.
 *
 * @param string $folder
 * @return array
 */
function wdir_files($folder) {
    $files = array();
    $dh = opendir($folder);
    while (($f = readdir($dh)) !== FALSE) {
	if (is_file($folder.$f)) {
	    $files[] = $f;
	}
    }
    closedir($dh);
    return $files;
}

function wdir($folder) {
    $o = '<table class="wdir">'
	    .'<thead>'
	    .'<tr>'
	    .'<td>Name</td>'
	    .'<td>Size</td>'
	    .'<td>Changed</td>'
	    .'</tr>'
	    .'</thead>'
	    .'<tbody>';
    foreach (wdir_files($folder) as $file) {
	$fn = $folder.$file;
	$o .= '<tr>'
		.'<td class="wdir_name"><a href="'.$fn.'">'.$file.'</a></td>'
		.'<td class="wdir_size">'.filesize($fn).' Bytes</td>'
		.'<td class="wdir_changed">'.filemtime($fn).'</td>'
		.'</tr>';
    }
    $o .= '</tbody></table>';
    return $o;
}

?>
