<?php defined('SYSPATH') or die('No direct script access.');?>
<form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'register'))?>">         
          <?=Form::errors()?>
          
          <div class="control-group">
            <label class="control-label"><?=__('Name')?></label>
            <div class="controls docs-input-sizes">
              <input class="input-medium" type="text" name="name" value="<?=Request::current()->post('name')?>" placeholder="<?=__('Name')?>">
            </div>
          </div>
          
          <div class="control-group">
            <label class="control-label"><?=__('Email')?></label>
            <div class="controls docs-input-sizes">
              <input class="input-medium" type="text" name="email" value="<?=Request::current()->post('email')?>" placeholder="<?=__('Email')?>">
            </div>
          </div>
     
          <div class="control-group">
            <label class="control-label"><?=__('New password')?></label>
            <div class="controls docs-input-sizes">
            <input class="input-medium" type="password" name="password1" placeholder="<?=__('Password')?>">
            </div>
          </div>
          
          <div class="control-group">
            <label class="control-label"><?=__('Repeat password')?></label>
            <div class="controls docs-input-sizes">
            <input class="input-medium" type="password" name="password2" placeholder="<?=__('Password')?>">
              <p class="help-block">
              		<?=__('Type your password twice')?>
              </p>
            </div>
          </div>
          
          <div class="form-actions">
          	<a class="btn"  data-dismiss="modal" data-toggle="modal" title="<?=__('Login')?>" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
				<i class="icon-user"></i> 
				<?=__('Login')?>
			</a>
            <button type="submit" class="btn btn-primary"><?=__('Register')?></button>
          </div>
          <?=Form::redirect()?>
          <?=Form::CSRF('register')?>
</form>      	