<?php defined('SYSPATH') or die('No direct script access.');?>
<?if(core::config('product.products_in_home') != 4):?>
<?if (count($products)>0):?>

    <section class="well featured-posts">
        <?if(core::config('product.products_in_home') == 0):?>
            <h2><?=__('Latest')?></h2>
        <?elseif(core::config('product.products_in_home') == 1):?>
            <h2><?=__('Featured')?></h2>
        <?elseif(core::config('product.products_in_home') == 2):?>
            <h2><?=__('Most popular')?></h2>
        <?elseif(core::config('product.products_in_home') == 3):?>
            <h2><?=__('Best rated')?></h2>
        <?endif?>
        <div class="row">
          <div id="slider-fixed-products" class="carousel slide">
            <div class="carousel-inner">
                <div class="active item">
                    <ul class="thumbnails">    
                    <?$i=0;
                    foreach ($products as $product):?>
                    <?if ($i%4==0 AND $i!=0):?></ul></div><div class="item"><ul class="thumbnails"><?endif?>
                    <li class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        <div class="thumbnail">
                            <a href="<?=Route::url('product', array('category'=>$product->category->seoname,'seotitle'=>$product->seotitle))?>" class="min-h">
                            <?if($product->get_first_image()!== NULL):?>
                                <?=HTML::picture(Core::S3_domain().$product->get_first_image(), ['w' => 140, 'h' => 140], ['1200px' => ['w' => '140', 'h' => '140'], '992px' => ['w' => '200', 'h' => '200']], ['alt' => HTML::chars($product->title)])?>
                            <?elseif(( $icon_src = $product->category->get_icon() )!==FALSE ):?>
                                <?=HTML::picture($icon_src, ['w' => 140, 'h' => 140], ['1200px' => ['w' => '140', 'h' => '140'], '992px' => ['w' => '200', 'h' => '200']], ['alt' => HTML::chars($product->title)])?>
                            <?else:?>
                                <img src="//www.placehold.it/200x200&text=<?=$product->category->name?>" alt="<?=$product->title?>"> 
                            <?endif?>
                            </a>
                          <div class="caption">
                            <h5><a href="<?=Route::url('product', array('category'=>$product->category->seoname,'seotitle'=>$product->seotitle))?>"><?=substr(Text::removebbcode($product->title), 0, 30)?></a></h5>
                            <p><?=substr(Text::removebbcode($product->description), 0, 30)?></p>
                            <a class="btn btn-success" href="<?=Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>">
                                <?if ($product->final_price()>0):?>
                                    <?=$product->formated_price()?>
                                <?elseif(!empty($product->file_name)):?>
                                    <?=__('Free Download')?>
                                <?else:?>
                                    <?=__('Get it for Free')?>
                                <?endif?>
                            </a>
                          </div>
                        </div>
                    </li>
                    <?$i++;
                    endforeach?>
                </ul>
              </div>
            </div>
            <a class="left carousel-control" href="#slider-fixed-products" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a class="right carousel-control" href="#slider-fixed-products" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
          </div>
        </div>
    </section>

<?endif?>
<?endif?>

<section class="well categories clearfix">
   <h2><?=__("Categories")?></h2>

        <?$i=0;
        foreach($categs as $c):?>
        <?if($c['id_category_parent'] == 1 && $c['id_category'] != 1):?>

        <ul class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <li class="cathead">
                <? $icon_src = new Model_Category($c['id_category']); $icon_src = $icon_src->get_icon(); if(( $icon_src )!==FALSE ):?>
                <a title="<?=$c['name']?>" href="<?=Route::url('list', array('category'=>$c['seoname']))?>">
                <img src="<?=$icon_src?>" alt="<?=$c['name']?>">
                </a>
                <?endif?>
                <a title="<?=$c['name']?>" href="<?=Route::url('list', array('category'=>$c['seoname']))?>"><?=mb_strtoupper($c['name']);?> <span class="badge badge-success pull-right"><?=$c['count']?></span></a>
            </li>
            
            <?foreach($categs as $chi):?>
                <?if($chi['id_category_parent'] == $c['id_category']):?>
                <li><a title="<?=$chi['name']?>" href="<?=Route::url('list', array('category'=>$chi['seoname']))?>">
                    <?=$chi['name'];?> <span class="badge pull-right"><?=$chi['count']?></span></a>
                </li>
                <?endif?>
             <?endforeach?>
        </ul>
        <?
        $i++;
            if ($i%3 == 0) echo '<div class="clear"></div>';
            endif?>
        <?endforeach?>

</section>
