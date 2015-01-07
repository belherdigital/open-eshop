<?php defined('SYSPATH') or die('No direct script access.');?>
<h3><?=$widget->text_title?></h3>
<div>
<?= FORM::open(Route::url('search'), array('class'=>'form-horizontal', 'method'=>'GET', 'action'=>'','enctype'=>'multipart/form-data'))?>
<!-- if categories on show selector of categories -->
    <div class="form-group">
        <div class="col-xs-10">  
            <?= FORM::label('product', __('Product Title'), array('class'=>'', 'for'=>'product'))?>
            <input type="text" id="title" name="title" class="form-control" value="" placeholder="<?=__('Search')?>">
        </div>
    </div>
<?if($widget->advanced != FALSE):?>
    <?if($widget->cat_items !== NULL):?>
        <div class="form-group">
            <div class="col-xs-10">
                <?= FORM::label('category', __('Categories'), array('class'=>'', 'for'=>'category_widget_search'))?>
                <select data-placeholder="<?=__('Categories')?>" name="category" id="category_widget_search" class="form-control">
                <option value="<?=core::request('category')?>"></option>
                <?function lili_search($item, $key,$cats){?>
                <?if ( count($item)==0 AND $cats[$key]['id_category_parent'] != 1):?>
                <option value="<?=$cats[$key]['seoname']?>" data-id="<?=$cats[$key]['id']?>" <?=(core::request('category') == $cats[$key]['seoname'])?"selected":''?> ><?=$cats[$key]['name']?></option>
                
                <?endif?>
                    <?if ($cats[$key]['id_category_parent'] == 1 OR count($item)>0):?>
                    <option value="<?=$cats[$key]['seoname']?>" <?=(core::request('category') == $cats[$key]['seoname'])?"selected":''?>> <?=$cats[$key]['name']?> </option>
                        <optgroup label="<?=$cats[$key]['name']?>">  
                        <? if (is_array($item)) array_walk($item, 'lili_search', $cats);?>
                        </optgroup>
                    <?endif?>
                <?}
                $cat_order = $widget->cat_order_items; 
                if (is_array($cat_order))
                    array_walk($cat_order , 'lili_search', $widget->cat_items);?>
                </select> 
            </div>
        </div>
    <?endif?>
<!-- end categories/ -->

    
        <div class="form-group">
             
            <div class="col-xs-10"> 
                <label class="" for="price-min"><?=__('Price from')?> </label>
                <input type="text" id="price-min" name="price-min" class="form-control" value="<?=core::get('price-min')?>" placeholder="<?=__('Price from')?>">
            </div>
        </div>

        <div class="form-group">
            
            <div class="col-xs-10">
                <label class="" for="price-max"><?=__('Price to')?></label>
                <input type="text" id="price-max" name="price-max" class="form-control" value="<?=core::get('price-max')?>" placeholder="<?=__('to')?>">
            </div>
        </div>
    
<?endif?>

<!-- /endcustom fields -->
<div class="clearfix"></div>

    <?= FORM::button('submit', __('Search'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('search')))?> 
<?= FORM::close()?>
</div>
