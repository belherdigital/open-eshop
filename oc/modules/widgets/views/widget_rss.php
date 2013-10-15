<?php defined('SYSPATH') or die('No direct script access.');?>
<h3><?=$widget->rss_title?></h3>
<ul>
<?foreach ($widget->rss_items as $item):?>
	<li><a target="_blank" href="<?=$item['link']?>" title="<?=$item['title']?>"><?=$item['title']?></a></li>
<?endforeach?>
</ul>
