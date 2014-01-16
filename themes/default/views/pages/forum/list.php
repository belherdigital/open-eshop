<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=$forum->name?></h1>
    <?if (!Auth::instance()->logged_in()):?>
    <a class="btn btn-success pull-right" data-toggle="modal" data-dismiss="modal" 
        href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
    <?else:?>
    <a class="btn btn-success pull-right" href="<?=Route::url('forum-new')?>?id_forum=<?=$forum->id_forum?>">
    <?endif?>
    <?=__('New Topic')?></a>
</div>

<?foreach($topics as $topic):?>
    <div class="media-body">
        <a title="<?=$topic->title?>" href="<?=Route::url('forum-topic', array('forum'=>$forum->seoname,'seotitle'=>$topic->seotitle))?>"><?=strtoupper($topic->title);?></a>
        <span class="badge badge-warning pull-right"><?=Date::format($topic->created)?></span>
        <span class="badge badge-success pull-right"><?=$topic->replies->count_all()?></span>
    </div>
<?endforeach?>