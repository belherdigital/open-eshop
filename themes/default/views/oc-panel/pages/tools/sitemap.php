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

      <div class="control-group">
        <label class="control-label"><?=__("Expire time")?>:</label>
        <div class="controls">
        <input  type="text" name="expires" value="<?=core::config('sitemap.expires')?>" class="span2"  /> Seconds
        </div>
      </div>

      <div class="control-group">
        <label class="control-label"><?=__("Update on publish")?>:</label>
          <div class="controls"> 
            <? $input = array("TRUE"=>"TRUE","FALSE"=>"FALSE");?>
              <?= FORM::select('on_post', $input, core::config('sitemap.on_post'), array(
              'placeholder' => 'on_post' ,
              'class' => 'span2', 
              'id' => 'on_post', 
              ))?>
          </div>
      </div>
 
        
          
      <div class="form-actions">
      	<a href="<?=Route::url('oc-panel')?>" class="btn"><?=__('Cancel')?></a>
        <button type="submit" class="btn btn-primary"><?=__('Save')?></button>
      </div>
	</form>    