<?php defined('SYSPATH') or die('No direct script access.');?>

<?if (isset($thanks_message)):?>
    <div class="page-header">
        <h1><?=$thanks_message->title?></h1>
    </div>
    <?=$thanks_message->description?>
<?else:?>
    <div class="page-header">
        <h1><?=__('Thanks for your purchase!')?></h1>
    </div>
<?endif?>

<?if ( core::config('general.analytics')!='' AND Kohana::$environment === Kohana::PRODUCTION AND is_numeric($price_paid)): ?>
    <script type="text/javascript">
    _gaq.push(['_trackEvent', 'Purchase', '<?=$product->seotitle?>', <?=round($price_paid,2)?>]);
    </script>
<?endif?>

<?if (isset($order) AND Auth::instance()->logged_in()):?>
    <?if(!empty($order->product->file_name)):?>
        <hr>
        <a title="<?=__('Download')?>" href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'download','id'=>$order->id_order))?>" 
        class="btn btn-success">
        <i class="icon-download icon-white"></i> <?=__('Download')?> <?=$order->product->version?></a>
    <?endif?>
<?else:?>
    <hr>
    <a class="btn btn-info btn-large" data-toggle="modal" data-dismiss="modal" 
        href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'Login'))?>#login-modal">
        <?=__('Login to proceed')?>
    </a>
<?endif?>