<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=$product->title?></h1>
</div>

<?if ($product->has_offer()):?>
    <span class="label label-success"><?=__('Offer')?> <?=$product->final_price().' '.$product->currency?> <del><?=$product->price.' '.$product->currency?></del></span>
    <p><?=__('Offer valid until')?> <?=Date::format($product->offer_valid)?></p>
<?else:?>
    <span class="label "><?=$product->final_price().' '.$product->currency?></span>
<?endif?>
    
<div class="well">
	<?=Text::bb2html($product->description,TRUE)?>
</div><!-- /well -->


<?if ($product->final_price()>0):?>
    <a class="btn btn-success btn-large" 
        href="<?=Route::url('default', array('controller'=>'paypal','action'=>'pay','id'=>$product->seotitle))?>">
        <?=__('Pay with Paypal')?></a>

    <?=Paymill::button($product)?>
<?else:?>

    <?if (!Auth::instance()->logged_in()):?>
    <a class="btn btn-info btn-large" data-toggle="modal" data-dismiss="modal" 
        href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'register'))?>#register-modal">
    <?else:?>
    <a class="btn btn-info btn-large"
        href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'free_download','id'=>$product->seotitle))?>">
    <?endif?>
        <?=__('Free Download')?>
    </a>

<?endif?>
