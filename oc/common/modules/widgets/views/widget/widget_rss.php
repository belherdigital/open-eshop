<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($widget->rss_title!=''):?>
	<div class="panel-heading">
		<h3 class="panel-title"><?=$widget->rss_title?></h3>
	</div>
<?endif?>

<div class="panel-body">
	<ul>
		<?foreach ($widget->rss_items as $item):?>
			<li><a target="_blank" href="<?=$item['link']?>" title="<?=HTML::chars($item['title'])?>"><?=$item['title']?></a></li>
		<?endforeach?>
	</ul>
</div>