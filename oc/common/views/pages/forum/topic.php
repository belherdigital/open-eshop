<div class="page-header">
    <h1><?=$topic->title?></h1>
    <span class="label label-info"><?=$topic->user->name?> <?=Date::fuzzy_span(Date::mysql2unix($topic->created))?></span>
    <?if($previous->loaded()):?>
        <a class="label" href="<?=Route::url('forum-topic',  array('seotitle'=>$previous->seotitle,'forum'=>$forum->seoname))?>" title="<?=HTML::chars($previous->title)?>">
        <i class="icon-white icon-backward glyphicon-backward glyphicon"></i> <?=$previous->title?></i></a>
    <?endif?>
    <?if($next->loaded()):?>
        <a class="label" href="<?=Route::url('forum-topic',  array('seotitle'=>$next->seotitle,'forum'=>$forum->seoname))?>" title="<?=HTML::chars($next->title)?>">
        <?=$next->title?> <i class="icon-white icon-forward glyphicon-forward glyphicon"></i></a>
    <?endif?>
</div>

    <div class="col-md-3 span2">
        <div class="thumbnail highlight">
            <img src="<?=$topic->user->get_profile_image()?>" width="120" height="120" alt="<?=HTML::chars($topic->user->name)?>">
            <div class="caption">
                <p>
                    <?if (in_array('profile', Route::all())) :?>
                        <a href="<?=Route::url('profile', array('seoname'=>$topic->user->seoname)) ?>">
                            <?=$topic->user->name?>
                        </a>
                    <?else :?>
                        <?=$topic->user->name?>
                    <?endif?>
                    <br>
                    <?=Date::fuzzy_span(Date::mysql2unix($topic->created))?><br>
                    <?=$topic->created?>
                </p>
            </div>
        </div> 
    </div>
    <div class="col-md-9 span6">
        <?if(Auth::instance()->logged_in()):?>
            <?if(Auth::instance()->get_user()->is_admin()):?>
                <a class="label label-warning pull-right" href="<?=Route::url('oc-panel', array('controller'=> 'topic', 'action'=>'update','id'=>$topic->id_post)) ?>">
                    <i class="glyphicon icon-white icon-edit glyphicon-edit"></i>
                </a>
            <?endif?>
        <?endif?>
        <div class="text-description"><?=Text::bb2html($topic->description,TRUE)?></div>
        <?if (Auth::instance()->logged_in()):?>
            <a  class="btn btn-primary" href="#reply_form"><?=_e('Reply')?></a>
        <?else:?>
            <a class="btn btn-primary" data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
                <?=_e('Reply')?>
            </a>
        <?endif?>
    </div>
<div class="clearfix"></div>
<div class="page-header"></div>

<?foreach ($replies as $reply):?>

    <div class="col-md-3 span2">
        <div class="thumbnail highlight">
            <img src="<?=$reply->user->get_profile_image()?>" width="120" height="120" alt="<?=HTML::chars($reply->user->name)?>">
            <div class="caption">
                <p>
                    <?if (in_array('profile', Route::all())) :?>
                        <a href="<?=Route::url('profile', array('seoname'=>$reply->user->seoname)) ?>">
                            <?=$reply->user->name?>
                        </a>
                    <?else :?>
                        <?=$reply->user->name?>
                    <?endif?>
                    <br>
                    <?=Date::fuzzy_span(Date::mysql2unix($reply->created))?><br>
                    <?=$reply->created?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-9 span6">
    <?if(Auth::instance()->logged_in()):?>
        <?if(Auth::instance()->get_user()->is_admin()):?>
            <a class="label label-warning pull-right" href="<?=Route::url('oc-panel', array('controller'=> 'topic', 'action'=>'update','id'=>$reply->id_post)) ?>">
                <i class="glyphicon icon-white icon-edit glyphicon-edit"></i>
            </a>
        <?endif?>
    <?endif?>
        <div class="text-description"><?=Text::bb2html($reply->description,TRUE)?></div>
        <a  class="btn btn-xs btn-primary" href="#reply_form"><?=_e('Reply')?></a>
    </div>

<div class="clearfix"></div>
<div class="page-header"></div>
<?endforeach?>
<?=$pagination?>


<?if($topic->status==Model_POST::STATUS_ACTIVE AND Auth::instance()->logged_in()):?>
<form class="well form-horizontal" id="reply_form" method="post" action="<?=Route::url('forum-topic',array('seotitle'=>$topic->seotitle,'forum'=>$forum->seoname))?>"> 
<h3><?=_e('Reply')?></h3>
  <?php if ($errors): ?>
    <p class="message"><?=_e('Some errors were encountered, please check the details you entered.')?></p>
    <ul class="errors">
        <?php foreach ($errors as $message): ?>
            <li><?php echo $message ?></li>
        <?php endforeach ?>
    </ul>
    <?php endif?>       

    <div class="form-group control-group">
        <div class="col-md-12">
            <textarea name="description" rows="10" class="form-control input-xxlarge" required><?=core::post('description',_e('Reply here'))?></textarea>
        </div>
    </div>

    <?if (core::config('advertisement.captcha') != FALSE OR core::config('general.captcha') != FALSE):?>
    <div class="form-group">
            <div class="col-md-4">
                <?if (Core::config('general.recaptcha_active')):?>
                    <?=Captcha::recaptcha_display()?>
                    <div id="recaptcha1"></div>
                <?else:?>
                    <?=_e('Captcha')?>*:<br />
                    <?=captcha::image_tag('new-reply-topic')?><br />
                    <?= FORM::input('captcha', "", array('class' => 'form-control', 'id' => 'captcha', 'required'))?>
                <?endif?>
            </div>
    </div>
    <?endif?>

    <button type="submit" class="btn btn-primary" name="submit"><?=_e('Reply')?></button>
</form>  
<?else:?>
<a class="btn btn-success pull-right" data-toggle="modal" data-dismiss="modal" 
        href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
        <?=_e('Login to reply')?>
</a>
<?endif?>  

