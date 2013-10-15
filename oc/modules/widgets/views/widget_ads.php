<?php defined('SYSPATH') or die('No direct script access.');?>
<h3><?=$widget->ads_title?></h3>
<ul>
<?foreach($widget->ads as $ad):?>
    <li><a href="<?=Route::url('ad',array('seotitle'=>$ad->seotitle,'category'=>$ad->category->seoname))?>" title="<?=$ad->title?>">
        <?=$ad->title?></a>
    </li>
<?endforeach?>
</ul>