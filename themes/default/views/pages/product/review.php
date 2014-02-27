<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header">
    <h1><?=$product->title.' '.__("Reviews")?></h1>
    <?if ($product->rate!==NULL):?>
        <?
            $rate_mark = round($product->rate,1);
            if($rate_mark == 1)
                $label = "danger";
            elseif($rate_mark == 2)
                $label = "warning";
            elseif($rate_mark == 3)
                $label = "info";
            else
                $label = "success";
        ?>
        <div class="col-md-2 label label-<?=$label?>">
            <h2 class="rating-num"><?=round($product->rate,1)?>/<?=Model_Review::RATE_MAX?> </h2>
            <div class="rating">
                <?for ($i=0; $i < round($product->rate,1); $i++):?>
                    <span class="glyphicon glyphicon-star"></span>
                <?endfor?>
            </div>
            <span class="glyphicon glyphicon-user"></span><?=count($reviews)?> <?=__('reviews')?>
        </div>
    <div class="clearfix"></div><br>
    <?endif?>
    <?if (!empty($product->url_demo)):?>
        <a class="btn btn-warning btn-small pull-right" href="<?=Route::url('product-demo', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>" ><?=__('Demo')?></a>
    <?endif?>

    <div class="button-space">
    <?if ($product->final_price()>0):?>
        <a class="btn btn-success review-pay-btn" 
            href="<?=Route::url('product-paypal', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>">
            <?=__('Pay with Paypal')?></a>
        <?=$product->alternative_pay_button()?>
        <?if (Theme::get('premium')==1):?>
            <?=StripeKO::button($product)?>
            <?=Paymill::button($product)?>
        <?endif?>
        
    <?else:?>
        <?if (!Auth::instance()->logged_in()):?>
        <a class="btn btn-info btn-large" data-toggle="modal" data-dismiss="modal" 
            href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'register'))?>#register-modal">
        <?else:?>
        <a class="btn btn-info review-pay-btn"
            href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'free','id'=>$product->seotitle))?>">
        <?endif?>
            <?if($product->has_file()==TRUE):?>
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
    <div class="col-md-12 well" >
        <div class="col-md-2">
            <img src="<?=$review->user->get_profile_image()?>" width="120px" height="120px">
            <p>
                <?=$review->user->name?><br>
                <?=Date::fuzzy_span(Date::mysql2unix($review->created))?><br>
                <?=$review->created?>
            </p>
        </div>
        <div class="col-md-10">
            <?if ($review->rate!==NULL):?>
        
            <div class="rating">
                <h1 class="rating-num"><?=round($review->rate,2)?>.0</h1>
                <?for ($i=0; $i < round($review->rate,1); $i++):?>
                    <span class="glyphicon glyphicon-star"></span>
                <?endfor?>
            </div>
       <?endif?>
            <p><?=Text::bb2html($review->description,TRUE)?></p>
        </div>
    </div>
    <?endforeach?>

<?elseif (count($reviews) == 0):?>
<div class="page-header">
    <h3><?=__('We do not have any reviews for this product')?></h3>
</div>
<?endif?>
