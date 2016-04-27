<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header">
    <?if ($category!==NULL):?>
        <h1><?=$category->name?></h1>
        <?if(( $icon_src = $category->get_icon() )!==FALSE ):?>
            <img src="<?=$icon_src?>" class="img-responsive" alt="<?=HTML::chars($category->name)?>">
        <?endif?>
        <?if (strlen($category->description)>0):?>
            <div class="well advise clearfix" id="advise">
                <p><?=$category->description?></p>
            </div><!--end of advise-->
        <?endif?>
    <?else:?>
        <h1><?=__('Listing')?></h1>
    <?endif?>
</div>

<div class="btn-group pull-right">
        <a href="#" id="list" class="btn btn-default btn-sm <?=(core::cookie('list/grid')==1)?'active':''?>">
            <span class="glyphicon glyphicon-th-list"></span><?=__('List')?>
        </a> 
        <a href="#" id="grid" class="btn btn-default btn-sm <?=(core::cookie('list/grid')==0)?'active':''?>">
            <span class="glyphicon glyphicon-th"></span><?=__('Grid')?>
        </a>
        <button type="button" id="sort" data-sort="<?=core::request('sort')?>" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
            <span class="glyphicon glyphicon-list-alt"></span><?=__('Sort')?> <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu" id="sort-list">
            <li><a href="?sort=title-asc"><?=__('Name (A-Z)')?></a></li>
            <li><a href="?sort=title-desc"><?=__('Name (Z-A)')?></a></li>
            <li><a href="?sort=price-asc"><?=__('Price (Low)')?></a></li>
            <li><a href="?sort=price-desc"><?=__('Price (High)')?></a></li>
            <li><a href="?sort=featured"><?=__('Featured')?></a></li>
            <li><a href="?sort=published-desc"><?=__('Newest')?></a></li>
            <li><a href="?sort=published-asc"><?=__('Oldest')?></a></li>
        </ul>
    </div>
<div class="clearfix"></div><br>
<?if(count($products)):?>

    <div id="products" class="row list-group">
        <?$i=1;
        foreach($products as $product ):?>    
            <div class="item <?=(core::cookie('list/grid')==1)?'list-group-item':'grid-group-item'?> col-xs-4 col-lg-4">
                <div class="thumbnail">
                    <a title="<?=HTML::chars($product->title)?>" href="<?=Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>">
                    <?if($product->get_first_image() !== NULL):?>
                        <?=HTML::picture(Core::S3_domain().$product->get_first_image('image'), ['w' => 292, 'h' => 292], ['1200px' => ['w' => '210', 'h' => '210'], '992px' => ['w' => '292', 'h' => '292']], ['alt' => HTML::chars($product->title)])?>
                    <?elseif(( $icon_src = $product->category->get_icon() )!==FALSE ):?>
                        <?=HTML::picture($icon_src, ['w' => 292, 'h' => 292], ['1200px' => ['w' => '210', 'h' => '210'], '992px' => ['w' => '292', 'h' => '292']], ['alt' => HTML::chars($product->title)])?>
                    <?else:?>
                        <img src="//www.placehold.it/292x292&text=<?=urlencode($product->category->name)?>" width="300" height="200" alt="<?=HTML::chars($product->title)?>">
                    <?endif?>
                    </a>
                    <div class="caption">
                        <h4><a href="<?=Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>"><?=substr($product->title, 0, 30)?></a></h4>
                        <p><?=Text::limit_chars(Text::removebbcode($product->description), (core::cookie('list/grid')==1)?255:30, NULL, TRUE)?></p>
                        <a class="btn btn-success" href="<?=Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>">
                        <span class="fa fa-shopping-cart"></span>
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
