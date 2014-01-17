<?php defined('SYSPATH') or die('No direct script access.');?>


<div class="page-header">
	<h1><?=__('Newsletter')?></h1>
  <a href="http://open-classifieds.com/2013/08/23/how-to-send-the-newsletter/" target="_blank"><?=__('Read more')?></a>
    <a class="btn btn-primary pull-right" href="<?=Route::url('oc-panel',array('controller'=>'settings','action'=>'email'))?>?force=1">
  <?=__('Email Settings')?></a>
<p><?=__('You can send a mass email to all active users.')?> <span class="badge badge-info"><?=$count?></span></p>
    </div>

    <form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'newsletter','action'=>'index'))?>">         
          <?=Form::errors()?>        
    

        <div class="form-group">
        <label class="col-md-2"><?=__('From')?>:</label>
        <div class="col-md-5">
        <input  type="text" name="from" value="<?=Auth::instance()->get_user()->name?>" class="col-md-6 form-control"  />
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-2"><?=__('From Email')?>:</label>
        <div class="col-md-5">
        <input  type="text" name="from_email" value="<?=Auth::instance()->get_user()->email?>" class="col-md-6 form-control"  />
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-2"><?=__('Subject')?>:</label>
        <div class="col-md-5">
        <input  type="text" name="subject" value="" class="col-md-6 form-control"  />
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-2"><?=__('Message')?>:</label>
        <div class="col-md-9">
        <textarea  name="description"  id="formorm_description" class="col-md-10 col-sm-10 col-xs-12 form-control" rows="15" ></textarea>
        </div>
      </div>
          
          
      <div class="form-actions">
        <a href="<?=Route::url('oc-panel')?>" class="btn btn-default"><?=__('Cancel')?></a>
        <button type="submit" class="btn btn-primary"><?=__('Send')?></button>
      </div>
    </form>    