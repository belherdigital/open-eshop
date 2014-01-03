<?php defined('SYSPATH') or die('No direct script access.');?>
<?if ($widget->text_title!=''):?>
<h3><?=$widget->text_title?></h3>
<?endif?>
<div id="tlkio" data-channel="<?=$widget->channel?>" style="width:100%;height:<?=$widget->height?>px;"></div>
<script async src="http://tlk.io/embed.js" type="text/javascript"></script>