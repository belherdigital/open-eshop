<?php defined('SYSPATH') or die('No direct script access.');?>

    <h2><?=$product->title?></h2>
    <?if ($product->has_offer()):?>
        <span class="label label-success mb-20 "><?=__('Offer')?> <?=$product->formated_price()?> <del><?=$product->price.' '.$product->currency?></del></span>
        <p><?=__('Offer valid until')?> <?=(Date::format((Model_Coupon::current()->loaded())?Model_Coupon::current()->valid_date:$product->offer_valid))?></p>
    <?else:?>
        <?if($product->final_price() != 0):?>
            <span class="label label-success mb-20 "><?=$product->formated_price()?></span>
        <?else:?>
            <span class="label label-success mb-20 "><?=__('Free')?></span>
        <?endif?>
    <?endif?>

    <?if($product->get_first_image() !== NULL):?>
    <div class="thumbnail ">
        <img src="<?=URL::base()?><?=$product->get_first_image('thumb')?>" class="" >
    </div>
    <?endif?>

    <div class="button-space">
    <?if ($product->final_price()>0):?>
        <a class="btn btn-success pay-btn full-w" target="_top"
            href="<?=Route::url('product-paypal', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>">
            <?=__('Pay with Paypal')?></a>
        <?=$product->alternative_pay_button()?>
    <?else:?>
        <?if (!Auth::instance()->logged_in()):?>
        <a class="btn btn-info pay-btn mb-20"  
            href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'register'))?>#register-modal">
        <?else:?>
        <a class="btn btn-info pay-btn full-w"
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
    <div class="clearfix"></div><br>
<!--     <span class="label label-info pull-right">
        <i class="icon-eye-open icon-white"></i> <?=$hits?>
    </span> -->
<div class="clearfix"></div>
    <ul id="mini-tabs" class="nav nav-tabs">
        <li class="active"><a href="#desc" data-toggle="tab"><?=__('Description')?></a></li>
        <li><a href="#details" data-toggle="tab"><?=__('Details')?></a></li>
    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane active" id="desc">
            <?=Text::bb2html($product->description,TRUE)?>
        </div>
        <div class="tab-pane" id="details">
            <ul class="mini-info">
                <p><?=__('Hits')?> : <?=$hits?></p>

                <?if ($product->has_file()==TRUE):?>
                    <p><?=__('Product format')?> : <?=strtoupper(strrchr($product->file_name, '.'))?> <?=__('file')?> </p>
                    <p><?=__('Product size')?> : <?=round(filesize(DOCROOT.'data/'.$product->file_name)/pow(1024, 2),2)?>MB</p>
                <?endif?>

                <?if ($product->support_days>0):?>
                    <p><?=__('Professional support')?> : <?=$product->support_days?> <?=__('days')?></p>
                <?endif?>

                <?if ($product->licenses>0):?>
                <p><?=__('Licenses')?> : <?=$product->licenses?>  
                    <?if ($product->license_days>0):?>
                        <?=__('valid')?> <?=$product->license_days?> <?=__('days')?>
                    <?endif?>
                </p>
                <?endif?>
            </ul>
            <div class="mt-20">
            <?=View::factory('coupon')?>
            </div>
        </div>
    </div>
    

<div class="clear"></div>