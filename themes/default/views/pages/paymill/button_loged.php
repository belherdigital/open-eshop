<?php defined('SYSPATH') or die('No direct script access.');?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<form action="<?=Route::url('default',array('controller'=>'paymill','action'=>'pay','id'=>$product->seotitle))?>" method="post">
    <script
        src="https://button.paymill.com/v1/"
        id="button"
        data-label="<?=__('Pay with Card')?>"
        data-title="<?=HTML::chars($product->title)?>"
        data-description="<?=Text::limit_chars(Text::removebbcode($product->description),30,NULL, TRUE)?>"
        data-amount="<?=Paymill::money_format($product->final_price())?>"
        data-currency="<?=$product->currency?>"
        data-submit-button="<?=__('Pay')?> <?=$product->formated_price()?>"
        data-elv="false"
        data-lang="en-GB"
        data-public-key="<?=Core::config('payment.paymill_public')?>"
        >
    </script>
</form>