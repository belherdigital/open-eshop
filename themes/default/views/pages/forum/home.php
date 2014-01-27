<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=__("Forums")?></h1>
    <?if (!Auth::instance()->logged_in()):?>
    <a class="btn btn-success pull-right" data-toggle="modal" data-dismiss="modal" 
        href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
    <?else:?>
    <a class="btn btn-success pull-right" href="<?=Route::url('forum-new')?>">
    <?endif?>
    <?=__('New Topic')?></a>
</div>

<?foreach($forums as $f):?>
<?if($f['id_forum_parent'] == 0):?>
    <div class="media-body">
        <a title="<?=$f['name']?>" href="<?=Route::url('forum-list', array('forum'=>$f['seoname']))?>"><?=strtoupper($f['name']);?></a>
        <span class="badge badge-warning pull-right"><?=(isset($f['last_message'])?Date::format($f['last_message']):'')?></span>
        <span class="badge badge-success pull-right"><?=$f['count']?></span>
        <?foreach($forums as $fhi):?>
            <?if($fhi['id_forum_parent'] == $f['id_forum']):?>
            <div class="media">
                <a title="<?=$fhi['name']?>" href="<?=Route::url('forum-list', array('forum'=>$fhi['seoname']))?>">
                <?=$fhi['name'];?></a>
                <span class="badge badge-warning pull-right"><?=(isset($fhi['last_message'])?Date::format($f['last_message']):'')?></span>
                <span class="badge badge-success pull-right"><?=$fhi['count']?></span>
            </div>
            <?endif?>
         <?endforeach?>
    </div>
<?endif?>
<?endforeach?>