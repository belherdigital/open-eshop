$('#stripe_button').click(function(){
  var token = function(res){
    var $input = $('<input type=hidden name=stripeToken />').val(res.id);
    $('form').append($input).submit();
  };

  StripeCheckout.open({
    key:         '<?=Core::config('payment.stripe_public')?>',
    amount:      <?=StripeKO::money_format($product->final_price())?>,
    currency:    '<?=$product->currency?>',
    name:        '<?=$product->title?>',
    description: '<?=substr(Text::removebbcode($product->description), 0, 30)?>',
    <?if (Auth::instance()->logged_in()):?>
    email:       '<?=Auth::instance()->get_user()->email?>',
     <?endif?>
    panelLabel:  'Checkout'
  });

  return false;
});