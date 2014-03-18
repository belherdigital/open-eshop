<?php defined('SYSPATH') or die('No direct script access.');?>

    
    <div class="well well-sm">
        <div class="row">
            <div class="col-xs-12 col-md-12 section-box">
                <h1>
                    <?=$product->title.' '.__("Reviews")?>
                </h1>
                <hr />
                <div class="row rating-desc">
                    <div class="col-md-12">
                        <?for ($i=0; $i < round($product->rate,1); $i++):?>
                            <span class="glyphicon glyphicon-star"></span>
                        <?endfor?>(<?=round($product->rate,1)?>/<?=Model_Review::RATE_MAX?>)<span class="separator">|</span>
                        <span class="glyphicon glyphicon-comment"></span><?=count($reviews)?> <?=__('reviews')?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="button-space pull-left">
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
    <?if (!empty($product->url_demo)):?>
        <a target="_blank" class="btn btn-warning btn-small pull-right" href="<?=Route::url('product-demo', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>" ><?=__('Demo')?></a>
    <?endif?>
    
    <div class="clearfix"></div>
    
<hgroup class="mb20"></hgroup>
<?if(count($reviews)):?>
    <?foreach ($reviews as $review):?>
    
    <article class="search-result row">
        <div class="col-xs-12 col-sm-12 col-md-3">
            <a title="<?=$review->user->name?>" class="thumbnail"><img src="<?=$review->user->get_profile_image()?>" alt="<?=__('Profile image')?>" height="140px"></a>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-2">
            <ul class="meta-search">
                <li><i class="glyphicon glyphicon-calendar"></i> <span><?=$review->created?></span></li>
                <li><i class="glyphicon glyphicon-time"></i> <span><?=Date::fuzzy_span(Date::mysql2unix($review->created))?></span></li>
                <li><i class="glyphicon glyphicon-user"></i> <span><?=$review->user->name?></span></li>
            <?if ($review->rate!==NULL):?>
        
            <div class="rating">
                <h1 class="rating-num"><?=round($review->rate,2)?>.0</h1>
                <?for ($i=0; $i < round($review->rate,1); $i++):?>
                    <span class="glyphicon glyphicon-star"></span>
                <?endfor?>
            </div>
            <?endif?>
            </ul>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-7">
            <p><?=Text::bb2html($review->description,TRUE)?></p>                        
            <!-- <span class="plus"><a href="#" title="Lorem ipsum"><i class="glyphicon glyphicon-plus"></i></a></span> -->
        </div>
        <span class="clearfix borda"></span>
    </article>

    <?endforeach?>

<?elseif (count($reviews) == 0):?>
<div class="page-header">
    <h3><?=__('We do not have any reviews for this product')?></h3>
</div>
<?endif?>



            
        
        
            
