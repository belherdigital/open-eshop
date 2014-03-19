<?php defined('SYSPATH') or die('No direct script access.');?>
<h3><?=$widget->forum_title?></h3>
<ul>

<?foreach($widget->forum as $forum):?>
    <li><a href="<?=Route::url('forum-list',array('forum'=>$forum->seoname))?>" title="<?=$forum->name?>">
        <?=$forum->name?></a>
    </li>
<?endforeach?>
</ul>