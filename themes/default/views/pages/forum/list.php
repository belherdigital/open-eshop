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
    <div class="clearfix"></div><br>
</div>

<?foreach($topics as $topic):?>
    <div class="media-body">
        <a title="<?=$topic->title?>" href="<?=Route::url('forum-topic', array('forum'=>$forum->seoname,'seotitle'=>$topic->seotitle))?>"><?=strtoupper($topic->title);?></a>
        <div class="clearfix"></div>
        <span class="label label-info pull-right"><?=Date::format($topic->created)?></span>
        <span class="label label-success pull-right"><?=$topic->replies->count_all()?></span>
        <?if (Auth::instance()->logged_in()):?>
            <?if(Auth::instance()->get_user()->id_role==Model_Role::ROLE_ADMIN):?>
                <a class="label label-warning" href="<?=Route::url('oc-panel', array('controller'=> 'topic', 'action'=>'update','id'=>$topic->id_post)) ?>">
                    <i class="glyphicon glyphicon-edit"></i>
                </a>
                
            <?endif?>
        <?endif?>
    </div>
    <div class="page-header"></div>
    <div class="clearfix"></div><br>
<?endforeach?>