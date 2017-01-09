<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($widget->links_title!=''):?>
	<div class="panel-heading">
		<h3 class="panel-title"><?=$widget->links_title?></h3>
	</div>
<?endif?>

<div class="panel-body">
	<ul>
		<?foreach($widget->url as $url):?>
		<li class='widget_link_li'>
			<a target="<?=$widget->target?>" href="<?=$url[0]?>">
				<?if(isset($url[1])):?>
					<?=$url[1];?>
				<?else:?>
					<?=$url[0];?>
				<?endif?>
			</a>
		</li>
		<?endforeach?>
	</ul>
</div>