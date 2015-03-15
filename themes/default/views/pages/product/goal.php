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
        ga('create', '<?=Core::config('general.analytics')?>');
        ga('require', 'ec');

        ga('ec:addProduct', {
          'id': '<?=$order->product->id_product?>',
          'name': '<?=HTML::chars($order->product->seotitle)?>',
          'category': '<?=HTML::chars($order->product->category->seoname)?>',
          'price': '<?=round($order->product->price,2)?>',
          'quantity': 1
        });

        // Transaction level information is provided via an actionFieldObject.
        ga('ec:setAction', 'purchase', {
          'id': '<?=$order->id_order?>',
          'affiliation': '<?=(Model_Affiliate::current()->loaded())?Model_Affiliate::current()->id_user:''?>',
          'revenue': '<?=round($product_price = (100*$order->amount)/(100+$order->VAT),2)?>',
          'tax': '<?=round($order->amount-$product_price,2)?>',
          'coupon': '<?=(is_numeric($order->id_coupon)?$order->coupon->name:'')?>'    // User added a coupon at checkout.
        });

        ga('send', 'pageview');     // Send transaction data with initial pageview.
    </script>
<?endif?>

<hr>
<?if (Auth::instance()->logged_in() AND $order->product->has_file()==TRUE):?>
        <a title="<?=__('Download')?>" href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'download','id'=>$order->id_order))?>" 
        class="btn btn-success">
        <i class="icon-download icon-white"></i> <?=__('Download')?> <?=$order->product->version?></a>
<?elseif(!Auth::instance()->logged_in()):?>
    <a class="btn btn-info btn-large" data-toggle="modal" data-dismiss="modal" 
        href="<?=Route::url('oc-panel',array('controller'=>'auth','action'=>'Login'))?>#login-modal">
        <?=__('Login to proceed')?>
    </a>
<?elseif(Auth::instance()->logged_in()):?>
        <a title="<?=__('Purchases')?>" href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'orders'))?>" 
        class="btn btn-success"> <?=__('Purchases')?> </a>
<?endif?>
