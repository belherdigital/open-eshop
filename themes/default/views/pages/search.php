<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="well advise clearfix">
    <h3><?=__('Advanced Search')?></h3>
    <?= FORM::open(Route::url('search'), array('class'=>'form-search', 'method'=>'GET', 'action'=>''))?>
        
        <input type="text" id="search" name="search" class="input-xxlarge input-medium search-query" value="<?=core::get('search')?>" placeholder="<?=__('Search')?>"> 
        <br>    
        <?=__('Price from')?> <input type="text" id="price-min" name="price-min" class="input-small" value="<?=core::get('price-min')?>" placeholder="0">
        <?=__('to')?> <input type="text" id="price-max" name="price-max" class="input-small" value="<?=core::get('price-max')?>" placeholder="100">

        <select name="category" id="category" class="input-xlarge" >
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
         
        <?= FORM::button('submit', __('Search'), array('type'=>'submit', 'class'=>'btn btn-primary pull-right', 'action'=>Route::url('search')))?> 

    <?= FORM::close()?>
</div>

<?if (count($products)>0):?>
    <h3><?=__('Search results')?></h3>
    <?=View::factory('pages/product/listing',array('pagination'=>$pagination,'products'=>$products,'category'=>NULL))?>
<?endif?>