<?php defined('SYSPATH') or die('No direct script access.');?>

$('#stripe_button').click(function(){
  var token = function(res){
    var $input = $('<input type=hidden name=stripeToken />').val(res.id);
    $('#stripe_form').append($input).submit();
  };

  StripeCheckout.open({
    key:         '<?=Core::config('payment.stripe_public')?>',
    amount:      <?=StripeKO::money_format($product->final_price())?>,
    currency:    '<?=$product->currency?>',
    name:        '<?=$product->title?>',
    description: '<?=Text::limit_chars(Text::removebbcode($product->description), 30, NULL, TRUE)?>',
    <?if (Auth::instance()->logged_in()):?>
    email:       '<?=Auth::instance()->get_user()->email?>',
     <?endif?>
    panelLabel:  'Checkout',
    token:       token
  });

  return false;
});