<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="well clearfix">
    <h1><?=__('Advanced Search')?></h1>
    <?= FORM::open(Route::url('search'), array('class'=>'form-search', 'method'=>'GET', 'action'=>''))?>
        <div class="row">
            <div class="">
                <div class="col-md-3 pl-0">
                    <input type="text" id="search" name="search" class="form-control" value="<?=HTML::chars(core::get('search'))?>" placeholder="<?=__('Name')?>">  
                </div>
            </div>
            <div class="">
                <div class="col-md-3 pl-0">
                    <select name="category" id="category" class="form-control remove_chzn" >
                    <option><?=__('Category')?></option>
                    <?function lili($item, $key,$cats){?>
                    <option value="<?=$cats[$key]['seoname']?>" <?=(core::get('category')==$cats[$key]['seoname']?'selected':'')?> >
                        <?=$cats[$key]['name']?></option>
                        <?if (count($item)>0):?>
                        <optgroup label="<?=HTML::chars($cats[$key]['name'])?>">    
                            <? if (is_array($item)) array_walk($item, 'lili', $cats);?>
                        <?endif?>
                    <?}array_walk($order_categories, 'lili',$categories);?>
                    </select>
                </div>
            </div>
            <div class="">
                <div class="col-md-2 pl-0">
                    <input type="text" id="price-min" name="price-min" class="form-control" value="<?=HTML::chars(core::get('price-min'))?>" placeholder="<?=__('Price from')?>">
                </div>
            </div>   
            <div class="">
                <div class="col-md-2 pl-0">
                    <input type="text" id="price-max" name="price-max" class="form-control" value="<?=HTML::chars(core::get('price-max'))?>" placeholder="<?=__('to')?>">
                </div>
            </div>
            <div class="">
                <div class="col-md-2 pl-0">
                    <?= FORM::button('submit', __('Search'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('search')))?> 
                </div>
            </div> 
        </div>
        

    <?= FORM::close()?>
</div>

<?if (count($products)>0):?>
    <h3><?=__('Search results')?></h3>
    <?=View::factory('pages/product/listing',array('pagination'=>$pagination,'products'=>$products,'category'=>NULL))?>
<?endif?>
