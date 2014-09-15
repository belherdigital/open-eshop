<?php defined('SYSPATH') or die('No direct script access.');?>
<h3><?=$widget->categories_title?></h3>

<?if($widget->cat_breadcrumb !== NULL):?>
<h5>
	<p>
		<?if($widget->cat_breadcrumb['id_parent'] != 0):?>
			<a href="<?=Route::url('list',array('category'=>$widget->cat_breadcrumb['parent_seoname']))?>" title="<?=$widget->cat_breadcrumb['parent_name']?>"><?=$widget->cat_breadcrumb['parent_name']?></a> - 
			<?=$widget->cat_breadcrumb['name']?>
		<?else:?>
			<a href="<?=Route::url('list',array('category'=>$widget->cat_breadcrumb['parent_seoname']))?>" title="<?=$widget->cat_breadcrumb['parent_name']?>"><?=__('Home')?></a> - 
			<?=$widget->cat_breadcrumb['name']?>
		<?endif?>
	</p>
</h5>
<?endif?>
<ul>
<?foreach($widget->cat_items as $cat):?>
    <li>
        <a href="<?=Route::url('list',array('category'=>$cat->seoname))?>" title="<?=$cat->name?>">
        <?=$cat->name?></a>
    </li>
<?endforeach?>
</ul>