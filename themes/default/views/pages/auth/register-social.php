<?php defined('SYSPATH') or die('No direct script access.');?>
<form class="well form-horizontal"  method="post" action="<?=Route::url('default',array('controller'=>'social',
                                                                                'action'=>'register',
                                                                                'id'    =>$provider)).'?uid='.$uid?>">         
          <?=Form::errors()?>
          <div class="control-group">
            <label class="control-label"><?=__('Name')?></label>
            <div class="controls docs-input-sizes">
              <input class="input-medium" type="text" name="name" value="<?=Core::post('name')?><?=Core::get('name')?>" placeholder="<?=__('Name')?>">
            </div>
          </div>
          
          <div class="control-group">
            <label class="control-label"><?=__('Email')?></label>
            <div class="controls docs-input-sizes">
              <input class="input-medium" type="text" name="email" value="<?=Core::post('email')?>" placeholder="<?=__('Email')?>">
            </div>
          </div>
     
          <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?=__('Register')?></button>
          </div>
          <?=Form::CSRF('register_social')?>
</form>      	
