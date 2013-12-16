<?php defined('SYSPATH') or die('No direct script access.');?>
<h3><?=$widget->links_title?></h3>
<ul>
	<?foreach($widget->url as $url):?>
	<li >
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