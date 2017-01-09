<?php defined('SYSPATH') or die('No direct script access.');?>	
	<div class="page-header">
		<h1><?=_e('2 Step Authentication')?></h1>
	</div>

    <form class="well form-horizontal auth"  method="post" action="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'2step'))?>">         
      <?=Form::errors()?>
      <div class="form-group">
        <label class="col-sm-3 control-label"><?=_e('Verification Code')?></label>
        <div class="col-md-5 col-sm-5">
          <input class="form-control" type="text" name="code" placeholder="<?=__('Code')?>">
        </div>
      </div>
      <div class="page-header"></div>
      <div class="col-sm-offset-3">
        <button type="submit" class="btn btn-primary"><?=_e('Send')?></button>
      </div>
      <?=Form::redirect()?>
      <?=Form::CSRF('2step')?>
    </form>         
