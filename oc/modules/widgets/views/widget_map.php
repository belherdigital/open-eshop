<?php defined('SYSPATH') or die('No direct script access.');?>
<h3><?=$widget->map_title?></h3>
<iframe frameborder="0" noresize="noresize" 
        height="<?=$widget->map_height+($widget->map_height*0.10)?>px" width="100%" 
        src="<?=Route::url('map')?>?height=<?=$widget->map_height?>&controls=0&zoom=<?=$widget->map_zoom?>">
</iframe>