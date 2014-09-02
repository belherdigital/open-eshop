<?php defined('SYSPATH') or die('No direct script access.');?>
<h3><?=$widget->products_title?></h3>
<ul>
<?foreach($widget->products as $product):?>
    <li><a href="<?=Route::url('product',array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>" title="<?=HTML::chars($product->title)?>">
        <?=$product->title?></a>
    </li>
<?endforeach?>
</ul>