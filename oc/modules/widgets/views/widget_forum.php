<?php defined('SYSPATH') or die('No direct script access.');?>
<h3><?=$widget->topic_title?></h3>
<ul>

<?foreach($widget->topic as $topic):?>
<?if($topic->forum->seoname != NULL):?>
    <li><a href="<?=Route::url('forum-topic', array('forum'=>$topic->forum->seoname,'seotitle'=>$topic->seotitle))?>" title="<?=$topic->title?>">  
        <?=$topic->title?></a>
    </li>
<?endif?>
<?endforeach?>
</ul>