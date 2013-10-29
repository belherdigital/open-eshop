<?php defined('SYSPATH') or die('No direct script access.');?>
<form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>">         
          <?=Form::errors()?>
          <div class="control-group">
            <label class="control-label"><?=__('Email')?></label>
            <div class="controls docs-input-sizes">
              <input class="input-medium" type="text" name="email" placeholder="<?=__('Email')?>">
            </div>
          </div>
     
          <div class="control-group">
            <label class="control-label"><?=__('Password')?></label>
            <div class="controls docs-input-sizes">
              <input class="input-medium" type="password" name="password" placeholder="<?=__('Password')?>">
              <p class="help-block">
              		<small><a data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'forgot'))?>#forgot-modal">
              			<?=__('Forgot password?')?>
              		</a></small>
              </p>
              <label class="checkbox">
                <input type="checkbox" name="remember" checked="checked"><?=__('Remember me')?>
              </label>
            </div>
          </div>
          
          <div class="form-actions">
          	<a class="btn" data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'register'))?>#register-modal">
            	<?=__('Register')?>
            </a>
            <button type="submit" class="btn btn-primary">
            	<i class="icon-user icon-white"></i><?=__('Login')?>
            </button>
          </div>
          <?=Form::redirect()?>
          <?=Form::CSRF('login')?>
          <?=View::factory('pages/auth/social')?>
</form>      	