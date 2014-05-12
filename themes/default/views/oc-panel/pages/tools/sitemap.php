<?php defined('SYSPATH') or die('No direct script access.');?>


    <div class="page-header">
        <h1><?=__('Sitemap')?></h1>
    <p><?=__('Last time generated')?> <?=Date::unix2mysql(Sitemap::last_generated_time())?></p>
    <p><?=__('Next sitemap')?> <?=Date::unix2mysql(Sitemap::last_generated_time()+core::config('sitemap.expires'))?></p>
    <a class="btn btn-primary pull-right" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'sitemap'))?>?force=1">
      <?=__('Generate')?></a>
    </div>

    <form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'sitemap'))?>">         
      
      <?=Form::errors()?>        

      <div class="form-group">
        <label class="control-label col-sm-2"><?=__("Expiry time")?>:</label>
        <div class="col-sm-4">
        <input  type="text" name="expires" value="<?=core::config('sitemap.expires')?>" class="col-md-2"  /> Seconds
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-sm-2"><?=__("Update on publish")?>:</label>
          <div class="col-sm-4"> 
            <? $input = array("1"=>"TRUE","0"=>"FALSE");?>
              <?= FORM::select('on_post', $input, core::config('sitemap.on_post'), array(
              'placeholder' => 'on_post' ,
              'class' => 'col-md-2', 
              'id' => 'on_post', 
              ))?>
          </div>
      </div>
      
      <div class="form-actions">
        <a href="<?=Route::url('oc-panel')?>" class="btn btn-default"><?=__('Cancel')?></a>
        <button type="submit" class="btn btn-primary"><?=__('Save')?></button>
      </div>
    </form>    