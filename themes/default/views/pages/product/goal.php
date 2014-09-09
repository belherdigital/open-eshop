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

<?if ( Kohana::$environment === Kohana::PRODUCTION AND core::config('general.analytics')!='' AND is_numeric($price_paid)): ?>
    <script type="text/javascript">
        _gaq.push(['_addTrans',
        '<?=session_id()?>',           // order ID - required
        '<?=HTML::chars($product->title)?>',  // affiliation or store name
        '<?=round($price_paid,2)?>',          // total - required
        '0',           // tax
        '',       // city
        ]);

        _gaq.push(['_addItem',
        '<?=session_id()?>',           // order ID - required
        '<?=$product->seotitle?>',           // SKU/code - required
        '<?=HTML::chars($product->title)?>',        // product name
        '<?=round($price_paid,2)?>',          // unit price - required
        '1'               // quantity - required
        ]);
        _gaq.push(['_set', 'currencyCode', '<?=$product->currency?>']);
        _gaq.push(['_trackTrans']);
    </script>
<?endif?>

<?if (isset($order) AND Auth::instance()->logged_in()):?>
    <?if($order->product->has_file()==TRUE):?>
        <hr>
        <a title="<?=__('Download')?>" href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'download','id'=>$order->id_order))?>" 
        class="btn btn-success">
        <i class="icon-download icon-white"></i> <?=__('Download')?> <?=$order->product->version?></a>
    <?endif?>
<?elseif(!Auth::instance()->logged_in()):?>
    <hr>
    <a class="btn btn-info btn-large" data-toggle="modal" data-dismiss="modal" 
        href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'Login'))?>#login-modal">
        <?=__('Login to proceed')?>
    </a>
<?elseif(Auth::instance()->logged_in()):?>
    <hr>
        <a title="<?=__('Purchases')?>" href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'orders'))?>" 
        class="btn btn-success"> <?=__('Purchases')?> </a>
<?endif?>
