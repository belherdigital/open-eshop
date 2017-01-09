<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($widget->text_title!=''):?>
    <div class="panel-heading">
        <h3 class="panel-title"><?=$widget->text_title?></h3>
    </div>
<?endif?>

<div class="panel-body">
    <div id="tlkio" data-channel="<?=$widget->channel?>" data-custom-css="<?=Core::config('general.base_url').'themes/default/css/widget-chat.css'?>" style="width:100%;height:<?=$widget->height?>px;"></div>
    <script async src="//tlk.io/embed.js" type="text/javascript"></script>
</div>