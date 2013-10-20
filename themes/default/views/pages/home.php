<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="well">
    <h3><?=__('Latest Products')?></h3>
   
    <ul class="thumbnails">
        <?$i=0;
        foreach($products as $product):?>
        <li class="span2">
            <div class="thumbnail latest_ads" >
                
                <?if(FALSE): //$product->get_first_image()!== NULL?>
                <a href="<?=Route::url('ad', array('controller'=>'ad','category'=>$product->category->seoname,'seotitle'=>$product->seotitle))?>">
                    <img src="<?=URL::base('http')?><?//$product->get_first_image()?>" class="img-polaroid">
                </a>
                <?endif?>
                <div class="caption">
                    <h5><a href="<?=Route::url('product', array('seotitle'=>$product->seotitle))?>"><?=$product->title?></a></h5>

                    <p ><?=substr(Text::removebbcode($product->description), 0, 30)?>
<a href="<?=Route::url('default', array('controller'=>'paypal','action'=>'pay','id'=>$product->seotitle))?>"><?=__('Paypal')?></a>
<?=Paymill::button($product)?>
                    </p>

                </div>
            </div>
        </li>     
        <?endforeach?>
    </ul>
</div>

<div class='well'>
    <h3><?=__("Categories")?></h3>
    <ul class="thumbnails">
        <?foreach($categs as $c):?>
        <?if($c['id_category_parent'] == 1 && $c['id_category'] != 1):?>
        <li class="span2 resized">
            <div class="category_box_title">
                <p><a title="<?=$c['name']?>" href="<?=Route::url('list', array('category'=>$c['seoname']))?>"><?=strtoupper($c['name']);?></a></p>
            </div>  
            <div class="well custom_box_content" style="padding: 8px 0;">
                <ul class="nav nav-list">
                    <?foreach($categs as $chi):?>
                        <?if($chi['id_category_parent'] == $c['id_category']):?>
                        <li><a title="<?=$chi['name']?>" href="<?=Route::url('list', array('category'=>$chi['seoname']))?>"><?=$chi['name'];?> <span class="count_ads"><span class="badge badge-success"><?=$chi['count']?></span></span></a></li>
                        <?endif?>
                     <?endforeach?>
                </ul>
            </div>
        </li>
        <?
        $i++;
            if ($i%3 == 0) echo '<div class="clear"></div>';
            ?>
        <?endif?>
        <?endforeach?>
    </ul>
</div>