<?php defined('SYSPATH') or die('No direct script access.');?>
<h3><?=$widget->page_title?></h3>
<ul>
<?foreach($widget->page_items as $page):?>
    <li><a href="<?=Route::url('page',array('seotitle'=>$page->seotitle))?>" title="<?=$page->title?>">
        <?=$page->title?></a>
    </li>
<?endforeach?>
</ul>