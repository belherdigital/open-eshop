<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header">
    <h1 class="forum-title pull-left"><?=$forum->name?></h1>
    <?if (!Auth::instance()->logged_in()):?>
    <a class="btn btn-success pull-right" data-toggle="modal" data-dismiss="modal" 
        href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
    <?else:?>
    <a class="btn btn-success pull-right" href="<?=Route::url('forum-new')?>?id_forum=<?=$forum->id_forum?>">
    <?endif?>
    <?=_e('New Topic')?></a>
    
    <?=View::factory('pages/forum/search-form')?>

    <div class="clearfix"></div><br>
    <div class="text-description"><?=Text::bb2html($forum->description,TRUE)?></div>
</div>

<table class="table table-hover" id="task-table">
    <thead>
        <tr>
            <th><?=_e('Topic')?></th>
            <th><?=_e('Created')?></th>
            <th><?=_e('Last Message')?></th>
            <th><?=_e('Replies')?></th>
            <?if (Auth::instance()->logged_in()):?>
                <?if(Auth::instance()->get_user()->is_admin()):?>
                    <th><?=_e('Edit')?></th>
                <?endif?>
            <?endif?>
        </tr>
    </thead>
    <tbody>
        <?foreach($topics as $topic):?>
            <tr class="success">
                <?
                //amount answers a topic got
                $replies = ($topic->count_replies>0)?$topic->count_replies:0;
                $page = '';
                //lets drive the user to the last page
                if ($replies>0)
                {
                    $last_page = round($replies/Controller_Forum::$items_per_page,0);
                    $page = ($last_page>0) ?'?page=' . $last_page : '';
                }
                   
                ?>

                <td><a title="<?=HTML::chars($topic->title)?>" href="<?=Route::url('forum-topic', array('forum'=>$forum->seoname,'seotitle'=>$topic->seotitle))?><?=$page?>"><?=mb_strtoupper($topic->title);?></a></td>
                <td width="10%"><span class="label label-info pull-right"><?=Date::format($topic->created, core::config('general.date_format'))?></span></td>
                <td width="15%"><span class="label label-warning pull-right"><?=Date::format($topic->last_message, core::config('general.date_format'))?></span></td>
                <td width="5%"><span class="label label-success pull-right"><?=$replies?></span></td>
                <?if (Auth::instance()->logged_in()):?>
                    <?if(Auth::instance()->get_user()->is_admin()):?>
                        <td width="10%">
                            <a class="label label-warning" href="<?=Route::url('oc-panel', array('controller'=> 'topic', 'action'=>'update','id'=>$topic->id_post)) ?>">
                                <span class="icon-edit icon-white glyphicon glyphicon-edit"></span>
                            </a>
                        </td>
                    <?endif?>
                <?endif?>
            </tr>
        <?endforeach?>
    </tbody>
</table>

<?=$pagination?>