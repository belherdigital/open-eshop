<?php defined('SYSPATH') or die('No direct script access.');?>


	<div class="page-header">
		<h1><?=__('Sitemap')?></h1>
    <p><?=__('Last time generated')?> <?=Date::unix2mysql(Core::cache('sitemap_last'))?></p>
    <p><?=__('Next sitemap')?> <?=Date::unix2mysql(Core::cache('sitemap_next'))?></p>
    <a class="btn btn-primary pull-right" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'sitemap'))?>?force=1">
      <?=__('Generate')?></a>
	</div>

	<form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'sitemap'))?>">         
      
      <?=Form::errors()?>        

      <div class="form-group">
        <label class="col-md-3 control-label"><?=__("Expire time")?>:</label>
        <div class="col-md-5">
        <input  type="text" name="expires" value="<?=core::config('sitemap.expires')?>" class="form-control"  /> Seconds
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-3 control-label"><?=__("Update on publish")?>:</label>
          <div class="col-md-5"> 
            <? $input = array("TRUE"=>"TRUE","FALSE"=>"FALSE");?>
              <?= FORM::select('on_post', $input, core::config('sitemap.on_post'), array(
              'placeholder' => 'on_post' ,
              'class' => 'form-control', 
              'id' => 'on_post', 
              ))?>
          </div>
      </div>
 
        
          
      <div class="form-actions">
      	<a href="<?=Route::url('oc-panel')?>" class="btn btn-default"><?=__('Cancel')?></a>
        <button type="submit" class="btn btn-primary"><?=__('Save')?></button>
      </div>
	</form>    