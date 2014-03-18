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

<div class="panel-body">
    <input type="text" class="form-control" id="task-table-filter" data-action="filter" data-filters="#task-table" placeholder="Filter Tasks" />
</div>
<table class="table table-hover" id="task-table">
    <thead>
        <tr>
            <th><?=__('Topic')?></th>
            <th><?=__('Created')?></th>
            <th><?=__('Topics')?></th>
            <?if (Auth::instance()->logged_in()):?>
                <?if(Auth::instance()->get_user()->id_role==Model_Role::ROLE_ADMIN):?>
                    <th><?=__('Edit')?></th>
                <?endif?>
            <?endif?>
        </tr>
    </thead>
    <tbody>
        <?foreach($topics as $topic):?>
            <tr class="success">
                <td width="70%"><a title="<?=$topic->title?>" href="<?=Route::url('forum-topic', array('forum'=>$forum->seoname,'seotitle'=>$topic->seotitle))?>"><?=strtoupper($topic->title);?></a></td>
                <td width="5%"><span class="label label-info pull-right"><?=Date::format($topic->created)?></span></td>
                <td width="5%"><span class="label label-success pull-right"><?=$topic->replies->count_all()?></span></td>
                <?if (Auth::instance()->logged_in()):?>
                    <?if(Auth::instance()->get_user()->id_role==Model_Role::ROLE_ADMIN):?>
                        <td width="10%">
                            <a class="label label-warning" href="<?=Route::url('oc-panel', array('controller'=> 'topic', 'action'=>'update','id'=>$topic->id_post)) ?>">
                                <span class="glyphicon glyphicon-edit"></span>
                            </a>
                        </td>
                    <?endif?>
                <?endif?>
            </tr>
        <?endforeach?>
    </tbody>
</table>