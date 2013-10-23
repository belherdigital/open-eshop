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



<a class="btn btn-success btn-large" 
    href="<?=Route::url('default', array('controller'=>'paypal','action'=>'pay','id'=>$product->seotitle))?>">
    <?=__('Pay with Paypal')?></a>

<?=Paymill::button($product)?>
