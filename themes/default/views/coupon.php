<?php defined('SYSPATH') or die('No direct script access.');?>

<form class="well form-inline"  method="post" action="<?=URL::current()?>">         
    <?if (Controller::$coupon!==NULL):?>
        <?=Form::hidden('coupon_delete',Controller::$coupon->name)?>
        <button type="submit" class="btn btn-warning"><?=__('Delete')?> <?=Controller::$coupon->name?></button>
        <p>
            <?=__('Discount off')?> <?=(Controller::$coupon->discount_amount==0)?round(Controller::$coupon->discount_percentage,0).'%':round(Controller::$coupon->discount_amount,0)?> <br>
            <?=Controller::$coupon->number_coupons?> <?=__('coupons left')?>, <?=__('valid until')?> <?=Date::format(Controller::$coupon->valid_date)?>.
            <?if(Controller::$coupon->id_product!=NULL):?>
                <?=__('only valid for')?>  <a target="_blank" href="<?=Route::url('product', array('seotitle'=>Controller::$coupon->product->seotitle,'category'=>Controller::$coupon->product->category->seoname)) ?>">
                        <?=Controller::$coupon->product->title;?></a>.
            <?endif?>
        </p>
    <?else:?>
    <div class="form-group">
        <input class="form-control" type="text" name="coupon" value="<?=Core::get('coupon')?><?=Core::get('coupon')?>" placeholder="<?=__('Coupon Name')?>">          
    </div>
        <button type="submit" class="btn btn-primary"><?=__('Add')?></button>
    <?endif?>      	
</form>