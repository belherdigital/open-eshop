<?php defined('SYSPATH') or die('No direct script access.');?>

    <?if($images = $product->get_images()):?>
    <div class="picture clearfix">
        <?foreach ($images as $path => $value):?>
        <?if( isset($value['thumb']) AND isset($value['image']) ):?>
            <a rel="prettyPhoto[gallery]" href="<?=URL::base('http')?><?= $value['image']?>">
                <figure><img src="<?=URL::base('http')?><?= $value['thumb']?>" ></figure>
            </a>
        <?endif?>   
        <?endforeach?>
        <div class="clear"></div>
    </div>
    <?endif?>

<?if ($product->has_offer()):?>
    <span class="label label-success"><?=__('Offer')?> <?=$product->final_price().' '.$product->currency?> <del><?=$product->price.' '.$product->currency?></del></span>
    <p><?=__('Offer valid until')?> <?=Date::format($product->offer_valid)?></p>
<?else:?>
    <?if($product->final_price() != 0):?>
        <span class="label mb-20"><?=$product->final_price().' '.$product->currency?></span>
    <?else:?>
        <span class="label label-success mb-20"><?=__('Free')?></span>
    <?endif?>
<?endif?>

<?if (!empty($product->url_demo)):?>
    <span class="label pull-right">
        <a href="<?=$product->url_demo?>" target="blank"><?=__('Demo')?></a>
    </span>
<?endif?>
    

<div class="well clearfix">
    <h2><?=$product->title?></h2>
	<?=Text::bb2html($product->description,TRUE)?>
</div><!-- /well -->    


<div>
    <span class="label label-info mb-20"><i class="icon-eye-open icon-white"></i> <?=$hits?></span>

    <?if (!empty($product->file_name)):?>
    <span class="label label-info mb-20">
        <?=strtoupper(strrchr($product->file_name, '.'))?> <?=__('file')?> 
        <?=round(filesize(DOCROOT.'data/'.$product->file_name)/pow(1024, 2),2)?>MB 
    </span>
    <?endif?>

    <?if ($product->support_days>0):?>
    <span class="label label-info">
    <?=$product->support_days?> <?=__('days professional support')?>
    </span>
    <?endif?>

    <?if ($product->licenses>0):?>
    <span class="label label-info">
    <?=$product->licenses?> <?=__('licenses')?> 
        <?if ($product->license_days>0):?>
            <?=$product->license_days?> <?=__('days valid')?>
        <?endif?>
    </span>
    <?endif?>
</div>

<div class="button-space">
<?if ($product->final_price()>0):?>
    <a class="btn btn-success pay-btn" 
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
        <?if(!empty($product->file_name)):?>
            <?=__('Free Download')?>
        <?else:?>
            <?=__('Get it for Free')?>
        <?endif?>
    </a>

<?endif?>
</div>
<div class="clear"></div>
<br/>
<div class="coupon">
<?=View::factory('coupon')?>
</div>
<?=$product->disqus()?>