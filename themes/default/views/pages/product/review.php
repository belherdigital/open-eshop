<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header">
    <h1><?=$product->title.' '.__("Reviews")?></h1>
    <?if ($product->rate!==NULL):?>
        <h2><span class="rating"><?=round($product->rate,1)?></span>/<?=Model_Review::RATE_MAX?> <?=__('from')?> <?=count($reviews)?> <?=__('reviews')?></h2>
    <?endif?>
    <?if (!empty($product->url_demo)):?>
        <a class="btn btn-warning btn-small pull-right" href="<?=Route::url('product-demo', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>" ><?=__('Demo')?></a>
    <?endif?>

    <div class="button-space">
    <?if ($product->final_price()>0):?>
        <a class="btn btn-success pay-btn" 
            href="<?=Route::url('product-paypal', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>">
            <?=__('Pay with Paypal')?></a>
        <?=$product->alternative_pay_button()?>
        <?=StripeKO::button($product)?>
        <?=Paymill::button($product)?>
    <?else:?>
        <?if (!Auth::instance()->logged_in()):?>
        <a class="btn btn-info btn-large" data-toggle="modal" data-dismiss="modal" 
            href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'register'))?>#register-modal">
        <?else:?>
        <a class="btn btn-info btn-large"
            href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'free','id'=>$product->seotitle))?>">
        <?endif?>
            <?if(!empty($product->file_name)):?>
                <?=__('Free Download')?>
            <?else:?>
                <?=__('Get it for Free')?>
            <?endif?>
        </a>
    <?endif?>
    </div>

</div>


<?if(count($reviews)):?>
    <?foreach ($reviews as $review):?>
        <div class="row well" >
        <div class="span2">
            <img src="<?=$review->user->get_profile_image()?>" width="120px">
            <p>
                <?=$review->user->name?><br>
                <?=Date::fuzzy_span(Date::mysql2unix($review->created))?><br>
                <?=$review->created?>
            </p>
        </div>
        <div class="span6">
            <h3><?=round($review->rate,1)?>/<?=Model_Review::RATE_MAX?></h3>
            <p><?=Text::bb2html($review->description,TRUE)?></p>
        </div>
    </div>
    <?endforeach?>

<?elseif (count($reviews) == 0):?>
<div class="page-header">
    <h3><?=__('We do not have any reviews for this product')?></h3>
</div>
<?endif?>
