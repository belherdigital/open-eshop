<?php defined('SYSPATH') or die('No direct script access.');?>

<form action="<?=Route::url('default',array('controller'=>'paymill','action'=>'pay','id'=>$product->seotitle))?>" method="post">
    <script
        src="https://button.paymill.com/v1/"
        id="button"
        data-label="<?=__('Pay with Credit Card')?>"
        data-title="<?=$product->title?>"
        data-description="<?=$product->description?>"
        data-amount="<?=Paymill::money_format($product->price)?>"
        data-currency="<?=$product->currency?>"
        data-submit-button="<?=__('Pay')?> <?=$product->price?> <?=$product->currency?>"
        data-elv="false"
        data-lang="en-GB"
        data-public-key="<?=Core::config('payment.paymill_public')?>">
    </script>
</form>