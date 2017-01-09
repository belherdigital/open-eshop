<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header">
<?if (core::config('general.cron') == FALSE):?>
    <a class="delete btn btn-success " 
        href="<?=Route::url('oc-panel', array('controller'=>'crontab','action'=>'status','id'=>1))?>"
        data-toggle="confirmation" 
        data-btnOkLabel="<?=__('Yes, definitely!')?>" 
        data-btnCancelLabel="<?=__('No way!')?>"
        title="<?=__('Activate')?>" >
        <i class="glyphicon   glyphicon-ok"></i> <?=__('Activate Cron')?>
    </a>
    <p><?=__('Or')?></p>
    <p><?=__('Set up your cron at your hosting / cPanel, every 5 minutes')?> (*/5 * * * *)</p>
    <input type="text" value="/usr/bin/php -f <?=DOCROOT?>oc/common/modules/cron/cron.php" />
    <p><?=__('Or')?></p>
    <input type="text" value="wget -O <?=Route::url('default', array('controller'=>'cron','action'=>'run','id'=>'now'))?>" />
<?else:?>
    <a class="delete btn btn-danger " 
        href="<?=Route::url('oc-panel', array('controller'=>'crontab','action'=>'status','id'=>0))?>"
        data-toggle="confirmation" 
        data-btnOkLabel="<?=__('Yes, definitely!')?>" 
        data-btnCancelLabel="<?=__('No way!')?>"
        title="<?=__('Disable')?>" >
        <i class="glyphicon   glyphicon-remove"></i> <?=__('Disable Cron')?>
    </a>
<?endif?>

</div>