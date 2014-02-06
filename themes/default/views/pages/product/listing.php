<?php defined('SYSPATH') or die('No direct script access.');?>
     
<?if ($category!==NULL):?>
    <?if (strlen($category->description>0)):?>
    <div class="well advise clearfix" id="advise">
        <p><?=Text::bb2html($category->description,TRUE)?></p> 
    </div><!--end of advise-->
    <?endif?>
<?endif?>


<div class="btn-group pull-right">
    <a href="#" id="list" class="btn btn-default btn-sm <?=($_COOKIE['list/grid']==1)?'active':''?>">
        <span class="glyphicon glyphicon-th-list"></span><?=__('List')?>
    </a> 
    <a href="#" id="grid" class="btn btn-default btn-sm <?=($_COOKIE['list/grid']==0)?'active':''?>">
        <span class="glyphicon glyphicon-th"></span><?=__('Grid')?>
    </a>
</div>
<div class="clearfix"></div><br>
<?if(count($products)):?>

    <div id="products" class="row list-group">
        <?$i=1;
        foreach($products as $product ):?>    
            <div class="item <?=($_COOKIE['list/grid']==1)?'list-group-item':'grid-group-item'?> col-xs-4 col-lg-4">
                <div class="thumbnail">
                    <a title="<?= $product->title;?>" href="<?=Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>">
                    <?if($product->get_first_image() !== NULL):?>
                        <img width="300px" height="200px" src="<?=URL::base()?><?=$product->get_first_image()?>" class="" >
                    <?else:?>
                        <img src="http://www.placehold.it/200x200&text=<?=$product->category->name?>">  
                    <?endif?>
                    </a>
                    <div class="caption">
                        <h4><a href="<?=Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>"><?=substr($product->title, 0, 30)?></a></h4>
                        <p><?=Text::limit_chars(Text::removebbcode($product->description), 30, NULL, TRUE)?></p>
                        <a class="btn btn-success" href="<?=Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>">
                        <?if ($product->final_price()>0):?>
                            <?=__('Buy Now')?> <?=$product->final_price().' '.$product->currency?>
                        <?elseif(!empty($product->file_name)):?>
                            <?=__('Free Download')?>
                        <?else:?>
                            <?=__('Get it for Free')?>
                        <?endif?>
                        </a>
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
