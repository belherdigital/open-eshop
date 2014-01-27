<?php defined('SYSPATH') or die('No direct script access.');?>
<form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'forgot'))?>">         
          <?=Form::errors()?>
          <div class="form-group">
            <label class="col-md-2 control-label"><?=__('Email')?></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input class="form-control" type="text" name="email" placeholder="<?=__('Email')?>">
            </div>
          </div>
          
          
          	<a class="btn btn-default" data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'register'))?>#register-modal">
            	<?=__('Register')?>
            </a>
            <button type="submit" class="btn btn-primary"><?=__('Send')?></button>

          <?=Form::CSRF('forgot')?>
</form>      	