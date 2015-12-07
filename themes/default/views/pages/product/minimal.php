<?php defined('SYSPATH') or die('No direct script access.');?>

    <h2><?=$product->title?></h2>
    <?if ($product->has_offer()):?>
        <p class="text-center"><span class="label label-success mb-20 "><?=__('Offer')?> <?=$product->formated_price()?> <del><?=$product->price.' '.$product->currency?></del></span></p>
        <p class="text-center"><?=__('Offer valid until')?> <?=(Date::format((Model_Coupon::current()->loaded())?Model_Coupon::current()->valid_date:$product->offer_valid))?></p>
    <?else:?>
        <p class="text-center">
            <?if($product->final_price() != 0):?>
                <span class="label label-success mb-20 "><?=$product->formated_price()?></span>
            <?else:?>
                <span class="label label-success mb-20 "><?=__('Free')?></span>
            <?endif?>
        </p>
    <?endif?>

    <?if($product->get_first_image() !== NULL):?>
    <div class="thumbnail ">
        <img src="<?=Core::S3_domain().$product->get_first_image('thumb')?>" class="" >
    </div>
    <?endif?>

    <div class="button-space">
        <?=View::factory('pages/product/buy-button',array('product'=>$product))?>
    </div>
    
<!--     <span class="label label-info pull-right">
        <i class="icon-eye-open icon-white"></i> <?=$hits?>
    </span> -->

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
                <?if (!empty($product->file_name)):?>
                    <li>
                        <?=mb_strtoupper(strrchr($product->file_name, '.'))?> <?=__('file')?> 
                        <?=round(filesize(DOCROOT.'data/'.$product->file_name)/pow(1024, 2),2)?>MB
                    </li>
                <?endif?>
                <?if ($product->support_days>0):?>
                    <li>
                        <?=$product->support_days?> <?=__('days professional support')?>
                    </li>
                <?endif?>
                <?if ($product->licenses>0):?>
                    <li>
                    <?=$product->licenses?> <?=__('licenses')?> 
                        <?if ($product->license_days>0):?>
                            <?=$product->license_days?> <?=__('days valid')?>
                        <?endif?>
                    </li>
                <?endif?>
            </ul>
            <div class="mt-20">
            <?=View::factory('coupon')?>
            </div>
        </div>
    </div>
    

<div class="clear"></div>