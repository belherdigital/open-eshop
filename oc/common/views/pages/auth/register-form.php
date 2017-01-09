<?php defined('SYSPATH') or die('No direct script access.');?>
<?=View::factory('pages/auth/social')?>
<form class="well form-horizontal register"  method="post" action="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'register'))?>">         
    <?=Form::errors()?>
    <div class="form-group">
        <label class="col-sm-4 control-label"><?=_e('Name')?></label>
        <div class="col-sm-8">
            <input class="form-control" type="text" name="name" value="<?=Request::current()->post('name')?>" placeholder="<?=__('Name')?>">
        </div>
    </div>
          
    <div class="form-group">
        <label class="col-sm-4 control-label"><?=_e('Email')?></label>
        <div class="col-sm-8">
            <input
                class="form-control" 
                type="text" 
                name="email" 
                value="<?=Request::current()->post('email')?>" 
                placeholder="<?=__('Email')?>" 
                data-domain='<?=(core::config('general.email_domains') != '') ? json_encode(explode(',', core::config('general.email_domains'))) : ''?>' 
                data-error="<?=__('Email must contain a valid email domain')?>"
            >
        </div>
    </div>
     
    <div class="form-group">
        <label class="col-sm-4 control-label"><?=_e('New password')?></label>
        <div class="col-sm-8">
            <input id="<?=isset($modal_form) ? 'register_password_modal' : 'register_password'?>" class="form-control" type="password" name="password1" placeholder="<?=__('Password')?>">
        </div>
    </div>
          
    <div class="form-group">
        <label class="col-sm-4 control-label"><?=_e('Repeat password')?></label>
        <div class="col-sm-8">
            <input class="form-control" type="password" name="password2" placeholder="<?=__('Password')?>">
            <p class="help-block">
                <?=_e('Type your password twice')?>
            </p>
        </div>
    </div>

    <?if(core::config('advertisement.tos') != ''):?>
        <div class="form-group">
            <div class="col-sm-8 col-sm-offset-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" required name="tos" id="tos"/> 
                        <a target="_blank" href="<?=Route::url('page', array('seotitle'=>core::config('advertisement.tos')))?>"> <?=_e('Terms of service')?></a>
                    </label>
                </div>
            </div>
        </div>
    <?endif?>
    
    <div class="form-group">
        <?if (core::config('advertisement.captcha') != FALSE OR core::config('general.captcha') != FALSE):?>
            <?if (Core::config('general.recaptcha_active')):?>
                <div class="col-sm-4"></div>
                <div class="col-sm-8">
                    <?=Captcha::recaptcha_display()?> 
                    <div id="<?=isset($recaptcha_placeholder) ? $recaptcha_placeholder : 'recaptcha3'?>"></div>
                </div>
            <?else:?>
                <label class="col-sm-4 control-label"><?=_e('Captcha')?>*:</label>
                <div class="col-sm-8">
                    <span id="helpBlock" class="help-block"><?=captcha::image_tag('register')?></span>
                    <?= FORM::input('captcha', "", array('class' => 'form-control', 'id' => 'captcha', 'required', 'data-error' => __('Captcha is not correct')))?>
                </div>
            <?endif?>
        <?endif?>
    </div>
    
    <hr>
    
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
            <ul class="list-inline">
                <li>
                    <button type="submit" class="btn btn-primary"><?=_e('Register')?></button>
                </li>
                <li>
                    <?=_e('Already Have an Account?')?>
                    <a data-dismiss="modal" data-toggle="modal"  href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
                        <?=_e('Login')?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <?=Form::redirect()?>
    <?=Form::CSRF('register')?>
</form>         
