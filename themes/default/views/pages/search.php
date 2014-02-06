<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="well clearfix">
    <?= FORM::open(Route::url('search'), array('class'=>'form-search', 'method'=>'GET', 'action'=>''))?>
        <div class="">
            <div class="col-md-3">
                <label><?=__('Name')?></label>
                <input type="text" id="search" name="search" class="form-control" value="<?=core::get('search')?>" placeholder="<?=__('Search')?>">  
            </div>
        </div>
        <div class="">
            <div class="col-md-3">
                <label><?=__('Category')?></label>
                <select name="category" id="category" class="form-control remove_chzn" >
                <option></option>
                <?function lili($item, $key,$cats){?>
                <option value="<?=$cats[$key]['seoname']?>" <?=(core::get('category')==$cats[$key]['seoname']?'selected':'')?> >
                    <?=$cats[$key]['name']?></option>
                    <?if (count($item)>0):?>
                    <optgroup label="<?=$cats[$key]['name']?>">    
                        <? if (is_array($item)) array_walk($item, 'lili', $cats);?>
                    <?endif?>
                <?}array_walk($order_categories, 'lili',$categories);?>
                </select>
            </div>
        </div>
        <div class="">
            <div class="col-md-2">
                <label><?=__('Price from')?></label>
                <input type="text" id="price-min" name="price-min" class="form-control" value="<?=core::get('price-min')?>" placeholder="0">
            </div>
        </div>   
        <div class="">
            <div class="col-md-2">
                <label><?=__('to')?></label>
                <input type="text" id="price-max" name="price-max" class="form-control" value="<?=core::get('price-max')?>" placeholder="100">
            </div>
        </div>
        <div class="adv-btn">
            <?= FORM::button('submit', __('Search'), array('type'=>'submit', 'class'=>'btn btn-primary pull-right', 'action'=>Route::url('search')))?> 
        </div> 
        

    <?= FORM::close()?>
</div>

<?if (count($products)>0):?>
    <h3><?=__('Search results')?></h3>
    <?=View::factory('pages/product/listing',array('pagination'=>$pagination,'products'=>$products,'category'=>NULL))?>
<?endif?>