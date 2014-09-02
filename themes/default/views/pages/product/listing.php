<?php defined('SYSPATH') or die('No direct script access.');?>
     
<?if ($category!==NULL):?>
    <?if (strlen($category->description>0)):?>
    <div class="well advise clearfix" id="advise">
        <p><?=$category->description?></p> 
    </div><!--end of advise-->
    <?endif?>
<?endif?>


<div class="btn-group pull-right">
    <a href="#" id="list" class="btn btn-default btn-sm <?=(core::cookie('list/grid')==1)?'active':''?>">
        <span class="glyphicon glyphicon-th-list"></span><?=__('List')?>
    </a> 
    <a href="#" id="grid" class="btn btn-default btn-sm <?=(core::cookie('list/grid')==0)?'active':''?>">
        <span class="glyphicon glyphicon-th"></span><?=__('Grid')?>
    </a>
</div>
<div class="clearfix"></div><br>
<?if(count($products)):?>

    <div id="products" class="row list-group">
        <?$i=1;
        foreach($products as $product ):?>    
            <div class="item <?=(core::cookie('list/grid')==1)?'list-group-item':'grid-group-item'?> col-xs-4 col-lg-4">
                <div class="thumbnail">
                    <a title="<?= $product->title;?>" href="<?=Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>">
                    <?if($product->get_first_image() !== NULL):?>
                        <img width="300" height="200" src="<?=URL::base()?><?=$product->get_first_image()?>" class="" >
                    <?elseif(( $icon_src = $product->category->get_icon() )!==FALSE ):?>
                        <img width="300" height="200" src="<?=$icon_src?>" alt="">
                    <?else:?>
                        <img src="http://www.placehold.it/200x200&text=<?=$product->category->name?>" width="200" height="200" alt="">  
                    <?endif?>
                    </a>
                    <div class="caption">
                        <h4><a href="<?=Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>"><?=substr($product->title, 0, 30)?></a></h4>
                        <p><?=Text::limit_chars(Text::removebbcode($product->description), (core::cookie('list/grid')==1)?255:30, NULL, TRUE)?></p>
                        <a class="btn btn-success" href="<?=Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>">
                        <?if ($product->final_price()>0):?>
                            <?=__('Buy Now')?> <?=$product->formated_price()?>
                        <?elseif($product->has_file()==TRUE):?>
                            <?=__('Free Download')?>
                        <?else:?>
                            <?=__('Get it for Free')?>
                        <?endif?>
                        </a>
                        <?if(core::config('product.number_of_orders')):?>
                            <div class="pull-right">
                                <p><span class="glyphicon glyphicon-shopping-cart"></span> <?=$product->number_of_orders()?></p>
                            </div>
                        <?endif?>
                    </div>
                </div>
            </div>
            <?if($i%3==0):?><div class="clearfix"></div><?endif?>
        <?$i++?>
        <?endforeach?>
    </div>

<?=$pagination?>
<?elseif (count($products) == 0):?>
<!-- Case when we dont have products for specific category / location -->
<div class="page-header">
    <h3><?=__('We do not have any product in this category')?></h3>
</div>

<?endif?>
