<?php defined('SYSPATH') or die('No direct script access.');?>
<h3><?=$widget->locations_title?></h3>

	<?if($widget->loc_breadcrumb !== NULL):?>
	<h5>
		<p>
			<?if($widget->loc_breadcrumb['id_parent'] != 0):?>
				<a href="<?=Route::url('list',array('location'=>$widget->loc_breadcrumb['parent_seoname'],'category'=>$widget->cat_seoname))?>" title="<?=$widget->loc_breadcrumb['parent_name']?>"><?=$widget->loc_breadcrumb['parent_name']?></a> - 
					<?=$widget->loc_breadcrumb['name']?>
			<?else:?>
				<a href="<?=Route::url('list',array('location'=>$widget->loc_breadcrumb['parent_seoname'],'category'=>$widget->cat_seoname))?>" title="<?=$widget->loc_breadcrumb['parent_name']?>"><?=__('Home')?></a> - 
				<?if($widget->loc_breadcrumb['id'] != 1):?>
					<?=$widget->loc_breadcrumb['name']?>
				<?endif?>
			<?endif?>
		</p>
	</h5>
	<?endif?>
	<ul>
	<?foreach($widget->loc_items as $loc):?>
	    <li>
	    	<a href="<?=Route::url('list',array('location'=>$loc->seoname,'category'=>$widget->cat_seoname))?>" title="<?=$loc->name?>">
	        <?=$loc->name?></a>
	    </li>
	<?endforeach?>
	</ul>
