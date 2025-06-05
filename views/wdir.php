<?php

use Plib\View;

if (!defined("CMSIMPLE_XH_VERSION")) {http_response_code(403); exit;}

/**
 * @var View $this
 * @var array<string,mixed> $config
 * @var string $script
 * @var string $head
 * @var list<object{name:string,icon:string,path:string,size:int,rsize:string,mtime:int}> $rows
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
  <tr>
    <td class="wdir_name" data-wdir="<?=$this->esc($row->name)?>"><?=$this->raw($row->icon)?><a href="<?=$this->esc($row->path)?>" target="_blank"><?=$this->esc($row->name)?></a></td>
    <td class="wdir_size" data-wdir="<?=$this->esc($row->size)?>"><?=$this->raw($row->rsize)?></td>
    <td class="wdir_modified" data-wdir="<?=$this->esc($row->mtime)?>"><?=$this->date("format_date", $row->mtime)?></td>
  </tr>
<?endforeach?>
</tbody>
</table>
