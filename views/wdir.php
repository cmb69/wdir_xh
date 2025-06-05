<?php

use Plib\View;

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var array $config
 * @var string $script
 */
?>

<script type="text/javascript">/* <![CDATA[ */
var WDIR = <?=json_encode($config)?>;
/* ]]> */</script>
<script type="text/javascript" src="<?=$this->esc($script)?>"></script>
