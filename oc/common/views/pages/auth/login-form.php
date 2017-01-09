<?php defined('SYSPATH') or die('No direct script access.');?>
<?=View::factory('pages/auth/social')?>
<form class="well form-horizontal auth" method="post" action="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>">         
    <?=Form::errors()?>
    <div class="form-group">
        <label class="col-sm-4 control-label"><?=_e('Email')?></label>
        <div class="col-sm-8">
            <input class="form-control" type="text" name="email" placeholder="<?=__('Email')?>">
        </div>
    </div>
     
    <div class="form-group">
        <label class="col-sm-4 control-label"><?=_e('Password')?></label>
        <div class="col-sm-8">
            <input class="form-control" type="password" name="password" placeholder="<?=__('Password')?>">
            <p class="help-block">
                <small><a data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'forgot'))?>#forgot-modal">
                    <?=_e('Forgot password?')?>
                </a></small>
            </p>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="remember" checked="checked"><?=_e('Remember me')?>
                </label>
            </div>
        </div>
    </div>
    
    <hr>

    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
            <ul class="list-inline">
                <li>
                    <button type="submit" class="btn btn-primary">
                        <?=_e('Login')?>
                    </button>
                </li>
                <li>
                    <?=_e('Don’t Have an Account?')?>
                    <a data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'register'))?>#register-modal">
                        <?=_e('Register')?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <?=Form::redirect()?>
    <?=Form::CSRF('login')?>
</form>         