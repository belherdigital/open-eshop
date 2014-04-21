<?php defined('SYSPATH') or die('No direct script access.');?>
<form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'register'))?>">         
          <?=Form::errors()?>
          
          <div class="form-group">
            <label class="col-md-1 control-label"><?=__('Name')?></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input class="form-control" type="text" name="name" value="<?=Request::current()->post('name')?>" placeholder="<?=__('Name')?>">
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-md-1 control-label"><?=__('Email')?></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input class="form-control" type="text" name="email" value="<?=Request::current()->post('email')?>" placeholder="<?=__('Email')?>">
            </div>
          </div>
          
          <div class="col-md-offset-1">
          	<a class="btn btn-default"  data-dismiss="modal" data-toggle="modal"  href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
				<i class="icon-user"></i> 
				<?=__('Login')?>
			</a>
            <button type="submit" class="btn btn-primary"><?=__('Register')?></button>
          </div>
          <?=Form::redirect()?>
          <?=Form::CSRF('register')?>
</form>      	