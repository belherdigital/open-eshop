<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=$topic->title?></h1>
    <p><?=$topic->user->name?> <?=Date::fuzzy_span(Date::mysql2unix($topic->created))?></p>
    <?if($previous->loaded()):?>
        <a class="badge" href="<?=Route::url('forum-topic',  array('seotitle'=>$previous->seotitle,'forum'=>$forum->seoname))?>" title="<?=$previous->title?>">
        <i class="icon-backward icon-white"></i> <?=$previous->title?></i></a>
    <?endif?>
    <?if($next->loaded()):?>
        <a class="badge" href="<?=Route::url('forum-topic',  array('seotitle'=>$next->seotitle,'forum'=>$forum->seoname))?>" title="<?=$next->title?>">
        <?=$next->title?> <i class="icon-forward icon-white"></i></a>
    <?endif?>
</div>

<div class="row">
    <div class="span2">
        <img src="<?=$topic->user->get_profile_image()?>" width="120px" height="120px">
        <p>
            <?=$topic->user->name?><br>
            <?=Date::fuzzy_span(Date::mysql2unix($topic->created))?><br>
            <?=$topic->created?>
        </p>
    </div>
    <div class="span6">
        <p><?=Text::bb2html($topic->description,TRUE)?></p>
        <?if(Auth::instance()->get_user()->id_role==Model_Role::ROLE_ADMIN):?>
            <a class="badge badge-warning pull-right" href="<?=Route::url('oc-panel', array('controller'=> 'topic', 'action'=>'update','id'=>$topic->id_post)) ?>">
                <i class="icon icon-edit"></i>
            </a>
        <?endif?>
    </div>
</div>
<hr>
<?foreach ($replies as $reply):?>
<div class="row" >
    <div class="span2">
        <img src="<?=$reply->user->get_profile_image()?>" width="120px" height="120px">
        <p>
            <?=$reply->user->name?><br>
            <?=Date::fuzzy_span(Date::mysql2unix($reply->created))?><br>
            <?=$reply->created?>
        </p>
    </div>
    <div class="span6">
        <p><?=Text::bb2html($reply->description,TRUE)?></p>
        <?if(Auth::instance()->get_user()->id_role==Model_Role::ROLE_ADMIN):?>
            <a class="badge badge-warning pull-right" href="<?=Route::url('oc-panel', array('controller'=> 'topic', 'action'=>'update','id'=>$reply->id_post)) ?>">
                <i class="icon icon-edit"></i>
            </a>
        <?endif?>
    </div>
</div>
<hr>
<?endforeach?>


<?if($topic->status==Model_POST::STATUS_ACTIVE AND Auth::instance()->logged_in()):?>
<form class="well form-horizontal"  method="post" action="<?=Route::url('forum-topic',array('seotitle'=>$topic->seotitle,'forum'=>$forum->seoname))?>"> 
<h3><?=__('Reply')?></h3>
  <?php if ($errors): ?>
    <p class="message"><?=__('Some errors were encountered, please check the details you entered.')?></p>
    <ul class="errors">
    <?php foreach ($errors as $message): ?>
        <li><?php echo $message ?></li>
    <?php endforeach ?>
    </ul>
    <?php endif ?>       

    <div class="control-group">
    <div class="controls">
    <textarea name="description" rows="10" class="col-md-6" required><?=core::post('description',__('Reply here'))?></textarea>
    </div>
    </div>

    <div class="control-group">
            <div class="controls">
                <?=__('Captcha')?>*:<br />
                <?=captcha::image_tag('new-reply-topic')?><br />
                <?= FORM::input('captcha', "", array('class' => 'input-xlarge', 'id' => 'captcha', 'required'))?>
            </div>
        </div>

    <button type="submit" class="btn btn-primary" name="submit"><?=__('Reply')?></button>
</form>  
<?else:?>
<a class="btn btn-success pull-right" data-toggle="modal" data-dismiss="modal" 
        href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
        <?=__('Login to reply')?>
</a>
<?endif?>  

