<?php

use Plib\View;

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var array $config
 * @var string $script
 * @var string $head
 * @var list<string> $rows
 */
?>

<div class="wdir_config" data-config='<?=$this->json($config)?>' style="display:none"></div>
<script type="module" src="<?=$this->esc($script)?>"></script>
<table class="wdir_table">
<thead>
  <tr>
    <td><?=$this->text("label_name")?></td>
    <td><?=$this->text("label_size")?></td>
    <td><?=$this->text("label_modified")?></td>
  </tr>
</thead>
<tbody>
<?foreach ($rows as $row):?>
<?=$this->raw($row)?>
<?endforeach?>
</tbody>
</table>
