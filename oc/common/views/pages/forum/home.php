<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header">
    <h1 class="forum-title pull-left"><?=_e("Forums")?></h1>
    
    <?if (!Auth::instance()->logged_in()):?>
        <a class="btn btn-success pull-right" data-toggle="modal" data-dismiss="modal" 
            href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
    <?else:?>
        <a class="btn btn-success pull-right" href="<?=Route::url('forum-new')?>">
    <?endif?>
        <?=_e('New Topic')?></a>
    
    <?=View::factory('pages/forum/search-form')?>
<div class="clearfix"></div>
</div>

<table class="table table-hover" id="task-table">
    <thead>
        <tr>
            <th><?=_e('Forum topic')?></th>
            <th><?=_e('Last Message')?></th>
            <th><?=_e('Topics')?></th>
        </tr>
    </thead>
    <tbody>
        <?foreach($forums as $f):?>
        <?if($f['id_forum_parent'] == 0):?>
            <tr class="success">
                <td><a title="<?=HTML::chars($f['name'])?>" href="<?=Route::url('forum-list', array('forum'=>$f['seoname']))?>"><?=mb_strtoupper($f['name']);?></a></td>
                <td width="15%"><span class="label label-warning pull-right"><?=(isset($f['last_message'])?Date::format($f['last_message'], core::config('general.date_format')):'')?></span></td>
                <td width="5%"><span class="label label-success pull-right"><?=number_format($f['count'])?></span></td>
            </tr>
                <?foreach($forums as $fhi):?>
                    <?if($fhi['id_forum_parent'] == $f['id_forum']):?>
                    <tr>
                        <th><a title="<?=HTML::chars($fhi['name'])?>" href="<?=Route::url('forum-list', array('forum'=>$fhi['seoname']))?>"><?=$fhi['name'];?></a></th>
                        <th width="15%"><span class="label label-warning pull-right"><?=(isset($fhi['last_message'])?Date::format($fhi['last_message'], core::config('general.date_format')):'')?></span></th>
                        <th width="5%"><span class="label label-success pull-right"><?=number_format($fhi['count'])?></span></th>
                    </tr>
                    <?endif?>
                <?endforeach?>
            <?endif?>
        <?endforeach?>
    </tbody>
</table>


