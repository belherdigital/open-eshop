<?php defined('SYSPATH') or die('No direct script access.');?>
<form class="well form-horizontal auth"  method="post" action="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'request'))?>">         
          <?=Form::errors()?>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?=_e('Name')?></label>
            <div class="col-md-5 col-sm-6">
              <input class="form-control" type="text" name="name" placeholder="<?=__('Name')?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?=_e('Email')?></label>
            <div class="col-md-5 col-sm-6">
              <input class="form-control" type="text" name="email" placeholder="<?=__('Email')?>">
            </div>
          </div>
          <div class="page-header"></div>
          <div class="col-sm-offset-2">
            <button type="submit" class="btn btn-primary"><?=_e('Send')?></button>
          </div>
          <?=Form::CSRF('request')?>
</form>      	