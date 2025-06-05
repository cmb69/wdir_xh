<?php

use Plib\View;

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var array $config
 * @var string $script
 */
?>

<div class="wdir_config" data-config='<?=$this->json($config)?>' style="display:none"></div>
<script type="module" src="<?=$this->esc($script)?>"></script>
