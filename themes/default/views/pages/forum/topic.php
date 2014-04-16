<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=$topic->title?></h1>
    <span class="label label-info"><?=$topic->user->name?> <?=Date::fuzzy_span(Date::mysql2unix($topic->created))?></span>
    <?if($previous->loaded()):?>
        <a class="label" href="<?=Route::url('forum-topic',  array('seotitle'=>$previous->seotitle,'forum'=>$forum->seoname))?>" title="<?=$previous->title?>">
        <i class="glyphicon-backward glyphicon"></i> <?=$previous->title?></i></a>
    <?endif?>
    <?if($next->loaded()):?>
        <a class="label" href="<?=Route::url('forum-topic',  array('seotitle'=>$next->seotitle,'forum'=>$forum->seoname))?>" title="<?=$next->title?>">
        <?=$next->title?> <i class="glyphicon-forward glyphicon"></i></a>
    <?endif?>
</div>

    <div class="col-md-3 ">
        <div class="thumbnail highlight">
            <img src="<?=$topic->user->get_profile_image()?>" width="120px" height="120px">
            <div class="caption">
                <p>
                    <?=$topic->user->name?><br>
                    <?=Date::fuzzy_span(Date::mysql2unix($topic->created))?><br>
                    <?=$topic->created?>
                </p>
            </div>
        </div> 
    </div>
    <div class="col-md-9">
        <?if(Auth::instance()->logged_in()):?>
            <?if(Auth::instance()->get_user()->id_role==Model_Role::ROLE_ADMIN):?>
                <a class="label label-warning pull-right" href="<?=Route::url('oc-panel', array('controller'=> 'topic', 'action'=>'update','id'=>$topic->id_post)) ?>">
                    <i class="glyphicon glyphicon-edit"></i>
                </a>
            <?endif?>
        <?endif?>
        <p><?=Text::bb2html($topic->description,TRUE)?></p>
        <a  class="btn btn-primary" href="#reply_form"><?=__('Reply')?></a>
    </div>
<div class="clearfix"></div>
<div class="page-header"></div>

<?foreach ($replies as $reply):?>

    <div class="col-md-3">
        <div class="thumbnail highlight">
            <img src="<?=$reply->user->get_profile_image()?>" width="120px" height="120px">
            <div class="caption">
                <p>
                    <?=$reply->user->name?><br>
                    <?=Date::fuzzy_span(Date::mysql2unix($reply->created))?><br>
                    <?=$reply->created?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-9">
    <?if(Auth::instance()->logged_in()):?>
        <?if(Auth::instance()->get_user()->id_role==Model_Role::ROLE_ADMIN):?>
            <a class="label label-warning pull-right" href="<?=Route::url('oc-panel', array('controller'=> 'topic', 'action'=>'update','id'=>$reply->id_post)) ?>">
                <i class="glyphicon glyphicon-edit"></i>
            </a>
        <?endif?>
    <?endif?>
        <p><?=Text::bb2html($reply->description,TRUE)?></p>
        <a  class="btn btn-xs btn-primary" href="#reply_form"><?=__('Reply')?></a>
    </div>

<div class="clearfix"></div>
<div class="page-header"></div>
<?endforeach?>
<?=$pagination?>


<?if($topic->status==Model_POST::STATUS_ACTIVE AND Auth::instance()->logged_in()):?>
<form class="well form-horizontal" id="reply_form" method="post" action="<?=Route::url('forum-topic',array('seotitle'=>$topic->seotitle,'forum'=>$forum->seoname))?>"> 
<h3><?=__('Reply')?></h3>
  <?php if ($errors): ?>
    <p class="message"><?=__('Some errors were encountered, please check the details you entered.')?></p>
    <ul class="errors">
        <?php foreach ($errors as $message): ?>
            <li><?php echo $message ?></li>
        <?php endforeach ?>
    </ul>
    <?php endif?>       

    <div class="form-group">
        <div class="col-md-12">
            <textarea name="description" rows="10" class="form-control" required><?=core::post('description',__('Reply here'))?></textarea>
        </div>
    </div>

    <div class="form-group">
            <div class="col-md-4">
                <?=__('Captcha')?>*:<br />
                <?=captcha::image_tag('new-reply-topic')?><br />
                <?= FORM::input('captcha', "", array('class' => 'form-control', 'id' => 'captcha', 'required'))?>
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

