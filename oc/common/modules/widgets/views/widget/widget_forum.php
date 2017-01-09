<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($widget->topic_title!=''):?>
    <div class="panel-heading">
        <h3 class="panel-title"><?=$widget->topic_title?></h3>
    </div>
<?endif?>

<div class="panel-body">
    <ul>
        <?foreach($widget->topic as $topic):?>
            <?if($topic->forum->seoname != NULL):?>
                <li><a href="<?=Route::url('forum-topic', array('forum'=>$topic->forum->seoname,'seotitle'=>$topic->seotitle))?>" title="<?=HTML::chars($topic->title)?>">  
                    <?=$topic->title?></a>
                </li>
            <?endif?>
        <?endforeach?>
    </ul>
</div>